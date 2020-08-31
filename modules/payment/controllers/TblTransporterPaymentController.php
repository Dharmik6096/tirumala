<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblTransporterPayment;
use app\modules\payment\models\TblTransporterPaymentHistory;
use app\modules\payment\models\TblTransporterPaymentSearch;
use app\modules\payment\models\TblTransporterPaymentDetailSearch;
use app\modules\payment\models\TblTransporterPaymentHeadDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\Model;

/**
 * TblTransporterPaymentController implements the CRUD actions for TblTransporterPayment model.
 */
class TblTransporterPaymentController extends \app\controllers\ChildController {

    public $freeAccessActions = ['payment-detail-primary', 'datewise-transporter-list'];

    /**
     * @inheritdoc
     */
    //  public $layout = "@app/themes/pcdf/layouts/paymentLayout.php";

    public function actionIndex() {
        $searchModel = new TblTransporterPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new TblTransporterPayment();
        $model->scenario = 'paymentprocess';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->from_date = date('Y-m-d', strtotime($model->from_date));
            $model->to_date = date('Y-m-d', strtotime($model->to_date));
            if ($model->transporter_type == 1) {
                $this->getSecondaryTPTSpData($model);
                return $this->redirect(['payment-adjust', 'TblTransporterPayment' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code, 'union_code' => $model->union_code]]);
            } else {
                $this->getPrimaryTPTSpData($model);
                return $this->redirect(['payment-adjust-primary', 'TblTransporterPayment' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code, 'union_code' => $model->union_code]]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->get());
        $model = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code])->one();
        if (!empty($model)) {
            $model->vendor_code = $model->transporterCode->vendor_code;
            if (Yii::$app->request->post()) {
                if (!empty(Yii::$app->request->post()['TblTransporterPayment']['adjust_amount'])) {
                    $historyModel = new TblTransporterPaymentHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $final_amount = $model->net_amount;
                    $model->load(Yii::$app->request->post());
                    $model->final_amount = $final_amount + $model->adjust_amount;
                    $transaction = $this->generalModel->saveTransaction([$model, $historyModel], ['Payment of ' . $model->transporterCode->transporter_name . '(' . $model->transporterCode->vendor_code . ')' . ' adjusted succesfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Payment of ' . $model->transporterCode->transporter_name . '(' . $model->transporterCode->vendor_code . ')' . ' adjusted succesfully']);
                    return $this->redirect(['index']);
                }
            }
            $searchModel = new TblTransporterPaymentDetailSearch();
            $searchModel->transporter_payment_code = $model->transporter_payment_code;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelHead = new TblTransporterPaymentHeadDetailSearch();
            $searchModelHead->transporter_payment_code = $model->transporter_payment_code;
            $dataProviderHead = $searchModelHead->search(Yii::$app->request->queryParams);
            return $this->render('payment-adjust-secondary', [
                        'model' => $model,
                        'vehicleDetail' => $dataProvider,
                        'headDetail' => $dataProviderHead,
                        'searchModel' => $searchModel,
                        'searchModelHead' => $searchModelHead
            ]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Receipt Detail not found.']);
            return $this->redirect(['create']);
        }
    }

    public function actionPaymentAdjustPrimary() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->get());
        $detailModel = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code])->all();
        if (!empty($detailModel)) {
            if (Yii::$app->request->post('TblTransporterPayment')) {
                $hisModel = [];
                $mainModel = [];
                foreach ($detailModel as $dh) {
                    $historyModel = new TblTransporterPaymentHistory();
                    Yii::$app->operation->history($dh, $historyModel, 'UPDATE');
                    $hisModel[] = $historyModel;
                }
                Model::loadMultiple($detailModel, Yii::$app->request->post());
                if (Model::validateMultiple($detailModel)) {
                    foreach ($detailModel as $dh) {
                        $dh->final_amount = $dh->net_amount + $dh->adjust_amount;
                        $mainModel[] = $dh;
                    }
                    $transaction = $this->generalModel->saveTransaction($mainModel, $hisModel, ['Payment of ' . $model->transporterCode->transporter_name . '(' . $model->transporterCode->vendor_code . ')' . ' adjusted succesfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
                }
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $detailModel,
                'pagination' => FALSE,
            ]);
            return $this->render('payment-adjust-primary', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
            ]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Route Km Detail not found.']);
            return $this->redirect(['create']);
        }
    }

    public function actionPaymentDetailPrimary() {
        if (!empty($_POST['code'])) {
            $model = TblTransporterPayment::find()->where(['transporter_payment_code' => $_POST['code']])->one();
            $searchModel = new TblTransporterPaymentDetailSearch();
            $searchModel->transporter_payment_code = $model->transporter_payment_code;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelHead = new TblTransporterPaymentHeadDetailSearch();
            $searchModelHead->transporter_payment_code = $model->transporter_payment_code;
            $dataProviderHead = $searchModelHead->search(Yii::$app->request->queryParams);
            return $this->renderAjax('payment-detail-primary', [
                        'model' => $model,
                        'vehicleDetail' => $dataProvider,
                        'headDetail' => $dataProviderHead,
                        'searchModel' => $searchModel,
                        'searchModelHead' => $searchModelHead
            ]);
        }
    }

    public function actionViewBill($union_code, $transporter_code, $from_date, $to_date, $transporter_type, $route_code = NULL) {
        $controls = [];
        $controls['p_union_code'] = $union_code;
        $controls['p_transporter_code'] = $transporter_code;
        $controls['p_from_date'] = $from_date;
        $controls['p_to_date'] = $to_date;
        if ($transporter_type == 1) {
            $controls['p_report_name'] = 'Secondary Transporter Bill';
            Yii::$app->general->printDocument($controls, 'transportationpayment/TransportationPaymentBill', 'TransportationPaymentBill', 'pdf');
        } else {
            $controls['p_route_code'] = $route_code;
            $controls['p_report_name'] = 'Primary Transporter Bill';
            Yii::$app->general->printDocument($controls, 'transportationpayment/PrimaryTransporterBill', 'PrimaryTransporterBill', 'pdf');
        }
    }

    private function getSecondaryTPTSpData($model) {
        $result = \Yii::$app->db->createCommand("{CALL sp_tpt_sec_payment (:union_code,:from_date,:to_date,:transporter_code)}")
                ->bindValue(':from_date', $model->from_date)
                ->bindValue(':to_date', $model->to_date)
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':transporter_code', $model->transporter_code);
        // ->bindValue(':primary_tpt_cost', $model->primary_tpt_cost);
        $query = $result->execute();
        return $query;
    }

    private function getPrimaryTPTSpData($model) {
        $result = \Yii::$app->db->createCommand("{CALL sp_tpt_primary_payment (:union_code,:from_date,:to_date,:transporter_code)}")
                ->bindValue(':from_date', $model->from_date)
                ->bindValue(':to_date', $model->to_date)
                ->bindValue(':union_code', $model->union_code)
                ->bindValue(':transporter_code', $model->transporter_code);
        $query = $result->execute();
        return $query;
    }

    public function actionDatewiseTransporterList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[2]) && !empty($parents[3])) {
                $mccs = new TblTransporterPayment();
                $data = $mccs->getdatewiseTransportersList($parents[0], $parents[1], $parents[2], $parents[3]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Finds the TblTransporterPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTransporterPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTransporterPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLockBill() {
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        $Models = [];
        if (!empty($this->model)) {
            $data = TblTransporterPayment::find()->where(['from_date' => $this->model->from_date, 'to_date' => $this->model->to_date, 'transporter_code' => $this->model->transporter_code, 'transporter_type' => $this->model->transporter_type])->all();
            foreach ($data as $bill) {
                $historyModel = new TblTransporterPaymentHistory();
                Yii::$app->operation->history($bill, $historyModel, UPDATE);
                $bill->status = 'locked';
                $Models[] = $historyModel;
                $Models[] = $bill;
            }
            $transaction = $this->generalModel->saveTransaction($Models, ['Billing', 'edit']);
            if ($transaction !== FALSE) {
                $record = ['status' => 'success', 'msg' => 'Billing locked successfully.'];
                return Json::encode($record);
            }
        }
        $record = ['status' => 'error', 'msg' => 'Billing could not be locked.'];
        return Json::encode($record);
    }

}
