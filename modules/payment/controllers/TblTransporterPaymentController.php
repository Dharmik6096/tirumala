<?php

namespace app\modules\payment\controllers;

use app\modules\organisation\models\TblMccPlant;
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

    public $freeAccessActions = ['payment-detail-primary', 'datewise-bmc-list', 'payment-adjust-primary', 'payment-adjust', 'datewise-transporter-list'];

    /**
     * @inheritdoc
     */
    //  public $layout = "@app/themes/emilk/layouts/paymentLayout.php";

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
            $model->transporter_type = 0;
            $model->from_date = date('Y-m-d', strtotime($model->from_date));
            $model->to_date = date('Y-m-d', strtotime($model->to_date));
            $mcc_plant_code = $model->mcc_plant_code;
            $bmc_code = $model->bmc_code;
            if(empty($model->mcc_plant_code)){
                $mccs = new TblMccPlant();
                $mccData = $mccs->getMCCList($model->plant_code);
                if(!empty($mccData)){
                    $mcc_plant_code = array_keys($mccData);
                }
            }
            if(empty($model->bmc_code)){
                $bmcData = $model->getdatewiseBmcList($model->union_code, $model->plant_code, $mcc_plant_code, $model->from_date, $model->to_date);
                if(empty($bmcData)){
                    $model->addError('bmc_code','All '.Yii::t('app', 'BMC').' payment process already done');
                }
                $bmc_code = array_keys($bmcData);
            }
            if(empty($model->getErrors())){
                $data = [];
                $data['union_code'] = $model->union_code;
                $data['plant_code'] = $model->plant_code;
                $data['mcc_plant_code'] = !empty($mcc_plant_code) ? implode(',',$mcc_plant_code) : '';
                $data['bmc_code'] = !empty($bmc_code) ? implode(',',$bmc_code) : '';
                $data['from_date'] = $model->from_date;
                $data['to_date'] = $model->to_date;
                $data['user_code'] = \Yii::$app->user->identity->user_code;
                $data['transporter_code'] = $model->transporter_code;
                Yii::$app->ClientPaymentConfig->processPayment('primary_tpt_payment', $data);
                return $this->redirect(['payment-adjust-primary', 'TblTransporterPayment' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'bmc_code' => $bmc_code, 'union_code' => $model->union_code, 'transporter_code' => $model->transporter_code, 'transporter_type' => 0]]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'transporter_type' => 0,
        ]);
    }

    public function actionCreateSecondary() {
        $model = new TblTransporterPayment();
        $model->scenario = 'sec_paymentprocess';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->transporter_type = 1;
            $model->from_date = date('Y-m-d', strtotime($model->from_date));
            $model->to_date = date('Y-m-d', strtotime($model->to_date));
            $data = [];
            $data['union_code'] = $model->union_code;
            $data['transporter_code'] = $model->transporter_code;
            $data['vehicle_code'] = $model->vehicle_code;
            $data['from_date'] = $model->from_date;
            $data['to_date'] = $model->to_date;
            $data['user_code'] = \Yii::$app->user->identity->user_code;
            Yii::$app->ClientPaymentConfig->processPayment('secondary_tpt_payment', $data);
            return $this->redirect(['payment-adjust', 'TblTransporterPayment' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code, 'union_code' => $model->union_code, 'vehicle_code' => $model->vehicle_code, 'transporter_type' => 1]]);
        }
        return $this->render('create', [
                    'model' => $model,
                    'transporter_type' => 1,
        ]);
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->get());
        $detailModel = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'transporter_code' => $model->transporter_code, 'transporter_type' => 1])
                ->andFilterWhere(['vehicle_code' => $model->vehicle_code])
                ->all();
        if (!empty($detailModel)) {
            $model = $detailModel[0];
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
                    $transaction = $this->generalModel->saveTransaction($mainModel, $hisModel, ['Payment of ' . $model->transporter_name . '(' . $model->vendor_code . ')' . ' adjusted succesfully', 'info']);
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
                        'transporter_type' => 1,
            ]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Receipt Detail not found.']);
            return $this->redirect(['create-secondary']);
        }
    }

    public function actionPaymentAdjustPrimary() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->get());
        $detailModel = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'bmc_code' => $model->bmc_code, 'transporter_type' => 0])
                ->andFilterWhere(['transporter_code' => $model->transporter_code])
                ->all();
        if (!empty($detailModel)) {
            $model->bmc_code = array_column($detailModel,'bmc_code');
            if (Yii::$app->request->post('TblTransporterPayment')) {
                $message = 'adjusted succesfully';
                if(is_array($model->bmc_code) && count($model->bmc_code) > 1) {
                    $message = 'Payment of All > '. $message;
                } else {
                    $message = 'Payment of ' . $model->bmcCode->bmc_name . '(' . $model->bmcCode->ref_code . ') ' . $message;
                }
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
                    $transaction = $this->generalModel->saveTransaction($mainModel, $hisModel, [$message, 'info']);
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
                        'transporter_type' => 0,
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

    public function actionView($id) {
        $model = TblTransporterPayment::find()->where(['transporter_payment_code' => $id])->one();
        $searchModel = new TblTransporterPaymentDetailSearch();
        $searchModel->transporter_payment_code = $model->transporter_payment_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelHead = new TblTransporterPaymentHeadDetailSearch();
        $searchModelHead->transporter_payment_code = $model->transporter_payment_code;
        $dataProviderHead = $searchModelHead->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'vehicleDetail' => $dataProvider,
                    'headDetail' => $dataProviderHead,
                    'searchModel' => $searchModel,
                    'searchModelHead' => $searchModelHead
        ]);
    }

    public function actionDatewiseBmcList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2]) && !empty($parents[3]) && !empty($parents[4])) {
                $mccs = new TblTransporterPayment();
                $data = $mccs->getdatewiseBmcList($parents[0], $parents[1], $parents[2], $parents[3], $parents[4]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
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
        if (!empty($this->model)) {
            $param = [];
            $param[] = $this->model->from_date;
            $param[] = $this->model->to_date;
            $param[] = $this->model->transporter_code;
            $param[] = $this->model->transporter_type;
            $param[] = \Yii::$app->user->identity->user_code;
            $output = \Yii::$app->general->getSpData('sp_disburse_transporter_payment', $param);
            if (!empty($output[0]) && !empty($output[0]['result'])) {
                $record = ['status' => 'success', 'msg' => 'Transporter Payment locked successfully.'];
                return Json::encode($record);
            }
        }
        $record = ['status' => 'error', 'msg' => 'Billing could not be locked.'];
        return Json::encode($record);
    }

    public function actionViewBillDetail($union_code, $transporter_code, $from_date, $to_date, $transporter_type, $route_code = NULL) {
        $controls = [];
        $controls['p_union_code'] = $union_code;
        $controls['p_transporter_code'] = $transporter_code;
        $controls['p_from_date'] = $from_date;
        $controls['p_to_date'] = $to_date;
        if ($transporter_type == 1) {
            $controls['p_report_name'] = 'Secondary Transporter Bill';
            Yii::$app->general->printDocument($controls, 'transportationpayment/TransportationPaymentBillDetail', 'TransportationPaymentBill', 'pdf');
        } else {
            $controls['p_route_code'] = $route_code;
            $controls['p_report_name'] = 'Primary Transporter Bill';
            Yii::$app->general->printDocument($controls, 'transportationpayment/PrimaryTransporterBill', 'PrimaryTransporterBill', 'pdf');
        }
    }

    public function actionDatewiseTransporterList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2])) {
                $mccs = new TblTransporterPayment();
                $data = $mccs->getdatewiseTransportersList($parents[0], $parents[1], $parents[2]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
