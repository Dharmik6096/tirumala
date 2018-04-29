<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblFederationsSearch;
use app\modules\organisation\models\TblFederationsHistory;
use app\modules\organisation\models\TblFederationsStateMapping;
use app\modules\organisation\models\TblFederationsStateMappingHistory;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\components\GeneralFunctions;

/**
 * TblFederationsController implements the CRUD actions for TblFederations model.
 */
class TblFederationsController extends ChildController {
    public $bankDetails;
    public $contactDetails;
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblFederations models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblFederations();
        $searchModel = new TblFederationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblFederations model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
//        return $this->render('view', [
//                    'model' => $this->findModel($id),
//        ]);
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name='federation';
        $bsearchModel->module_code=$id;
        $bdataProvider= $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name='federation';
        $csearchModel->module_code=$id;
        $cdataProvider= $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblFederations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblFederations();
        $this->viewFile = 'create';
        
        $this->bankDetails=new TblBankDetails();
        $this->contactDetails=new TblContactDetails();
        $this->contactDetails->scenario='additional';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->federation_code = $this->model->getCode();
            $this->setModel($this->model);                     
             
            $mapping=[];
            if(!empty($this->bankDetails->bank_code))
                    {
                        $this->bankDetails->load(Yii::$app->request->post());
                        $this->bankDetails->setModel('federation', $this->model->federation_code);
                        $this->bankDetails->scenario='bank_selected';
                        array_push($mapping, $this->bankDetails);
                    }
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('federation', $this->model->federation_code);
             array_push($mapping, $this->contactDetails);

            // set mapping table
            $modelMapping = new TblFederationsStateMapping();
            $this->setMapping($modelMapping);
            array_push($mapping, $modelMapping);
            $transaction = $this->generalModel->saveTransaction([$this->model], [$mapping], ['federation', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblFederations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $oldState = $this->model->state_code;

        if (Yii::$app->request->post()) {
            $historyModel = new TblFederationsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);

            //mapping table

            $mappingList = [];

            if ((!$this->model->getCheckUnionExist())) {
                if ($oldState != $this->model->state_code) {
                    $oldModel = TblFederationsStateMapping::find()->where(['federation_code' => $this->model->federation_code, 'state_code' => $oldState])->one();
                    if ($oldModel) {
                        $mappingHistory = new TblFederationsStateMappingHistory();
                        Yii::$app->operation->history($oldModel, $mappingHistory, DELETE);
                        array_push($mappingList, $mappingHistory);
                        array_push($mappingList, $oldModel);
                    }
                    $modelMapping = new TblFederationsStateMapping();
                    $this->setMapping($modelMapping);
                    array_push($mappingList, $modelMapping);
                }
            }
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $mappingList, ['federation', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblFederations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_federations', 'tbl_federation_state', 'tbl_federation_state_history', Yii::$app->request->post('id'), 'federation_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblFederationsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], ['TblFederationsStateMapping', 'TblFederationsStateMappingHistory'], 'federation_code');
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblFederations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblFederations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblFederations::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMapStates($id) {
        $model = new TblFederationsStateMapping();
        $modelFederation = $this->findModel($id);
        $state_list = $model->getState($id);
        if (Yii::$app->request->post()) {

            $state_code = Yii::$app->request->post('TblFederationsStateMapping')['state_code'];

            if (empty($state_code)) {
                $model->addError('federation_code', 'Please select at least one state.');
                $state_code = [];
                return $this->render('_map_states', [
                            'model' => $model, 'state_list' => $state_list['value'], 'selected' => $state_code, 'federation_name' => $modelFederation->federation_name, 'modelFederation' => $modelFederation, 'defaultValue' => $modelFederation->state_code
                ]);
            }

            $data = $model->find()->where(['federation_code' => $id, 'is_active' => 1, 'is_delete' => 0])->all();
            $returnedArray = \yii\helpers\ArrayHelper::map($data, 'state_code', 'state_code');


            $oldAssignments = array_keys($returnedArray);
            $newAssignments = array_intersect(array_flip($state_list['value']), $state_code);

            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_diff($oldAssignments, $newAssignments);

            $record = $this->generalModel->mappingTransaction($toRevoke, $toAssign, ['TblFederationsStateMapping', 'TblFederationsStateMappingHistory'], ['federation_code', 'state_code'], $id);

            if ($record) {
                Yii::$app->display->message(true, 'federation state mapping', 'edit');
                return $this->redirect(['index']);
            }
        }
        return $this->render('_map_states', [
                    'model' => $model, 'state_list' => $state_list['value'], 'selected' => $state_list['selected'], 'federation_name' => $modelFederation->federation_name, 'modelFederation' => $modelFederation, 'defaultValue' => $modelFederation->state_code
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->federation_code]);
    }
    
    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
            'bankDetails'=>$this->bankDetails,
            'contactDetails'=>$this->contactDetails
            ]);
    }

    private function setModel() {
        $this->model->federation_name = ucwords($this->model->federation_name);
        $this->model->registration_date = ($this->model->registration_date == '') ? null : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
        $this->model->contact_person_pan_no = strtoupper($this->model->contact_person_pan_no);
    }

    private function setMapping(&$modelMapping) {
        $modelMapping->federation_code = $this->model->federation_code;
        $modelMapping->state_code = $this->model->state_code;
        $modelMapping->is_active = $this->model->is_active;
    }

    public function actionUploadLogo($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = '_upload_logo';
        if (Yii::$app->request->post()) {
            $gen = new GeneralFunctions();
            $gen->uploadImage($model, 'logo_path');
        }
        return $this->render($this->viewFile, ['model' => $this->model]);
    }
    
    public function actionBankDetails($id)
    {
        $bankDetails=new TblBankDetails();
        $bankDetails->scenario='additional';
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name='federation';
        $searchModel->module_code=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelFederation = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
            'model' => $bankDetails,
            'id'=>$id,
            'module'=>'federation',
            'dist'=>$modelFederation->district_code,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dist_field'=>'tblfederations-district_code'
            
        ]);
        
    }
    
    public function actionContactDetails($id)
    {
        $contactDetails=new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name='federation';
        $searchModel->module_code=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
            'model' => $contactDetails,
            'id'=>$id,
            'module'=>'federation',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        
    }

}
