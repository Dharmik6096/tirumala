<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblBillHeadTransaction;
use app\modules\vsp\models\TblBillHeadTransactionSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcs;
use app\modules\vsp\models\TblBillHead;
use yii\widgets\ActiveForm;
use app\modules\dcsoperation\models\TblMember;
use app\modules\vsp\models\TblBillHeadTransactionHistory;

/**
 * TblBillHeadTransactionController implements the CRUD actions for TblBillHeadTransaction model.
 */
class TblBillHeadTransactionController extends ChildController {

    public $freeAccessActions = ['validate-member', 'validate-dcs'];

    /**
     * Lists all TblBillHeadTransaction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBillHeadTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBillHeadTransaction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblBillHeadTransactionSearch();
        $searchModel->bill_head_txn_code = $id;
        $dataProvider = $searchModel->installmentsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel
        ]);
    }

    /**
     * Creates a new TblBillHeadTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBillHeadTransaction();
        $searchModel = new TblBillHeadTransactionSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->get());
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d', strtotime($this->model->transaction_date)) : '';
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], [], ['Bill Head Transaction', 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'temp_collection_data' => [], 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'temp_collection_data' => [], 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSocietyBulkInsert() {
        $this->viewFile = 'society_bulk';
        $this->model = new TblBillHeadTransaction();
        if (!empty(Yii::$app->request->post()) && $this->model->load(Yii::$app->request->post())) {
            $main = [];
            if (!empty(Yii::$app->request->post()['dcs_code'])) {
                foreach (Yii::$app->request->post()['dcs_code'] as $key => $dcs) {
                    $model = new TblBillHeadTransaction();
                    $model->load(Yii::$app->request->post());
                    $model->dcs_code = $dcs;
                    array_push($main, $model);
                }
                $transaction = $this->generalModel->saveTransaction($main, [], ['Bill Head Transaction', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {
                $this->model->addError('dcs_code', Yii::t('app', 'You must select atleast one society.'));
            }
        }
        return $this->customRender();
    }

    protected function findModel($id) {
        if (($model = TblBillHeadTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSocietyWiseTransaction() {
        $this->viewFile = 'society_transaction';
        $this->model = new TblBillHeadTransaction();
        if (!empty(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            if (!empty($data['amount'])) {
                $main = [];
                foreach ($data['amount'] as $key => $amount) {
                    if ($amount > 0) {
                        if (empty($data['bill_head_transaction'][$key])) {
                            $this->addData($data, $key, $main);
                        } else {
                            $editModel = $this->findModel($data['bill_head_transaction'][$key]);
                            if ($editModel->amount != $amount) {
                                $historyDetail = new TblBillHeadTransactionHistory();
                                Yii::$app->operation->history($editModel, $historyDetail, UPDATE);
                                array_push($main, $historyDetail);
                                $editModel->amount = $amount;
                                array_push($main, $editModel);
                            }
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction($main, [], ['Bill Head Transaction', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
            $this->model->addError('amount', Yii::t('app', 'You have to Add atleast one Amount.'));
        }
        return $this->customRender();
    }

    private function addData($data, $key, &$main) {
        $model = new TblBillHeadTransaction();
        $model->load($data);
        $model->bill_head_code = $data['bill_head'][$key];
        $model->amount = $data['amount'][$key];
        $model->no_installment = 1;
        array_push($main, $model);
    }

    public function actionTransactionBillHead() {
        $model = new TblBillHeadTransaction();
        $bill_head_model = new TblBillHead();
        $bill_head_data = $bill_head_model->getAllBillHead(Yii::$app->request->post('dcs_code'));
        $bill_head_code = \yii\helpers\ArrayHelper::getColumn($bill_head_data, function($element) {
                    return $element['bill_head_code'];
                });
        $detail_data = $model->getData(Yii::$app->request->post('dcs_code'), Yii::$app->request->post('payment_cycle_code'), $bill_head_code);
        return $this->renderAjax('_society_trn_bill_head', ['model' => $model, 'bill_head_model' => $bill_head_model, 'bill_head_data' => $bill_head_data, 'detail_data' => $detail_data]);
    }

    public function actionBillHeadType() {
        $headModel = new TblBillHead();
        $type = $headModel->billHeadType(Yii::$app->request->post('bill_head_code'));
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $type]);
    }

    public function actionListGrid() {
        $searchModel = new TblBillHeadTransactionSearch();
        $searchModel->grid_filter = false;
        $searchModel->setAttributes(Yii::$app->request->get('TblBillHeadTransaction'));
        $dataProvider = $searchModel->gridsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionCreateMemberBillDetail() {
        $this->model = new TblBillHeadTransaction();
        $searchModel = new TblBillHeadTransactionSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->get());
        $this->viewFile = 'create';
        $this->model->customer_type = 'DCS';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->customer_type = 'MEMBER';
            $this->model->scenario = 'memberBillHead';
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d', strtotime($this->model->transaction_date)) : '';
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], [], ['Member Bill Head Transaction', 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'temp_collection_data' => [], 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'temp_collection_data' => [], 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->render('create_member_bill', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionValidateMember() {
        $member = Yii::$app->request->post('member_code');
        $model = new TblMember();
        $data = $model->validMember($member);
        if (!empty($data)) {
            return Json::encode(['status' => 'success', 'member_details' => $data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionMemberListGrid() {
        $searchModel = new TblBillHeadTransactionSearch();
        $searchModel->grid_filter = false;
        $searchModel->setAttributes(Yii::$app->request->get('TblBillHeadTransaction'));
        $dataProvider = $searchModel->membergridsearch([]);
        return $this->renderAjax('_member_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionValidateDcs() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $bmc = Yii::$app->request->post('bmc_code');
        $dcs = Yii::$app->request->post('dcs_code');
        $union = Yii::$app->request->post('union_code');
        $type = Yii::$app->request->post('customer_type');
        $headModel = new TblBillHeadTransaction();
        $headModel->union_code = $union;
        $headModel->customer_type = $type;
        if (!empty($type) && strtolower($type) != 'dcs') {
            $headModel->customer_code = $dcs;
            $data = Yii::$app->general->validateCustomerCode($headModel);
            $headModel->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($dcs, $bmc);
            $headModel->customer_code = $data;
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getCustomer($headModel, $type);
            $response['status'] = 'success';
            $response['data'] = $name;
            $response['code'] = $headModel->customer_code;
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

}
