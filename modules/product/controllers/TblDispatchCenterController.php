<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblDispatchCenter;
use app\modules\product\models\TblDispatchCenterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblDispatchCenterHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblDispatchCenterApplicability;
use app\modules\globalmaster\models\TblCustomerType;
use \app\modules\product\models\TblDispatchCenterApplicabilityHistory;
use webvimark\modules\UserManagement\models\User;
use app\models\UserHistory;

/**
 * TblDispatchCenterController implements the CRUD actions for TblDispatchCenter model.
 */
class TblDispatchCenterController extends \app\controllers\ChildController {

    /**
     * Lists all TblDispatchCenter models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDispatchCenterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDispatchCenter model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDispatchCenter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDispatchCenter();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->dispatch_center_name = ucwords($this->model->dispatch_center_name);
            $this->model->dispatch_center_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->dispatch_center_type_code = implode(',', $this->model->dispatch_center_type_code);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Dispatch Center', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDispatchCenter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblDispatchCenterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->dispatch_center_name = ucwords($this->model->dispatch_center_name);
            $this->model->dispatch_center_type_code = implode(',', $this->model->dispatch_center_type_code);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Product', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        $this->model->dispatch_center_type_code = explode(',', $this->model->dispatch_center_type_code);
        return $this->render('update', [
                    'model' => $this->model
        ]);
    }

    /**
     * Deletes an existing TblDispatchCenter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete() {
//        $this->model = $this->findModel(Yii::$app->request->post('id'));
//        $historyModel = new TblDispatchCenterHistory();
//        Yii::$app->operation->history($this->model, $historyModel, DELETE);
//        
//        $saveModel = [];
//        $deleteModel = [];
//        $saveModel[] = $historyModel;
//        $deleteModel[] = $this->model;
//        $dispatchApplicabilities = TblDispatchCenterApplicability::find()->where(['dispatch_center_code' => $this->model->dispatch_center_code])->all();
//        if(!empty($dispatchApplicabilities)){
//            foreach ($dispatchApplicabilities as $applicability) {
//                $deleteModel[] = $applicability;
//                $applicabiltyHistoryModel = new TblDispatchCenterApplicabilityHistory();
//                Yii::$app->operation->history($applicability, $applicabiltyHistoryModel, DELETE);
//                $saveModel[] = $applicabiltyHistoryModel;
//            }
//        }
//        $users = User::find()->where(['dispatch_center_code' => $this->model->dispatch_center_code])->all();
//        if(!empty($users)){
//            $userIds = [];
//            foreach ($users as $user) {
//                $userIds[] = $user->id;
//                $user->dispatch_center_code = NULL;
//                $saveModel[] = $user;
//                $userHistoryModel = new UserHistory();
//                Yii::$app->operation->history($user, $userHistoryModel, UPDATE);
//                $saveModel[] = $userHistoryModel;
//            }
//            $orgMappingUser = \app\models\TblUserOrganizationMapping::find()->where(['in', 'user_id', $userIds])->all();
//            foreach ($orgMappingUser as $value) {
//                $deleteModel[] = $value;
//            } 
//        }
//        $message = 'Dispatch Center';
//        $type = 'delete';
//        $record = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
//
//        Yii::$app->response->format = trim(Response::FORMAT_JSON);
//        return Json::encode($record);
//    }

    /**
     * Finds the TblDispatchCenter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDispatchCenter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDispatchCenter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDispatchCenterApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblDispatchCenterApplicability();
        $appModel->with_wef_date = false;
        $appModel->with_applicable_code = true;
        $appModel->save_applicability_child = true;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'dispatch_center_code';
        $appModel->field_value = $id;
        $appModel->options = ['tanker_rate'];
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->trans_label = Yii::t('app', 'dispatch center applicability');
        $appModel->header_title = ' [Dispatch Center: ' . $model->dispatch_center_name . '] ';
        $appModel->fields = [
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
//            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
//            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                },'filter' => true],
            'mcc_name' => ['view' => ['grid'], 'value' => function($model) {
                    if ($model->applicable_for == 'PLANT') {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    } else if ($model->applicable_for == 'MCC') {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    } else if ($model->applicable_for == 'BMC') {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    } else if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->mainCustomerCode, 'customer_name');
                    }
                }],
        ];
        $value = ['DCS' => Yii::t('app', 'DCS')];
        $appModel->actions = ['delete' => ['option' => 'dispatch_center_applicability_code,dispatch_center_applicability_code,tbl-dispatch-center/delete-applicability,allowDelete()']];
        $appModel->dcs_filters = $value;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = TblDispatchCenterApplicability::find()->where(['dispatch_center_applicability_code' => Yii::$app->request->post('id')])->one();
        $localHistory = new TblDispatchCenterApplicabilityHistory();
        Yii::$app->operation->history($model, $localHistory, DELETE);

        $saveModel = [];
        $deleteModel = [];
        $saveModel[] = $localHistory;
        $deleteModel[] = $model;

        $subQuery = User::find()->where(['dispatch_center_code' => $model->dispatch_center_code])->select('id');
        $query = \app\models\TblUserOrganizationMapping::find()->where(['in', 'user_id', $subQuery])->andWhere(['organization_code' => $model->applicable_code]);
        $orgMappingUser = $query->all();
        foreach ($orgMappingUser as $value) {
            $deleteModel[] = $value;
        }

        $message = 'Dispatch Center Applicability';
        $type = 'delete';
        $record = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
        if ($record == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
