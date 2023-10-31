<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\welfarescheme\models\TblSchemeMasterHistory;
use app\modules\welfarescheme\models\TblSchemeMasterSearch;
use app\modules\welfarescheme\models\TblSchemeCriteria;
use app\modules\welfarescheme\models\TblSchemeCriteriaSearch;
use app\modules\welfarescheme\models\TblSchemeDocumentMapping;
use app\modules\welfarescheme\models\TblSchemeDocumentMappingSearch;
use app\modules\welfarescheme\models\TblSchemeDocumentMappingHistory;
use app\modules\welfarescheme\models\TblDocumentMasterInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\welfarescheme\models\TblSchemeApprovalStages;
use app\modules\welfarescheme\models\TblSchemeApprovalStagesHistory;
use app\modules\welfarescheme\models\TblSchemeApprovalStagesSearch;
use app\modules\usermanagement\models\User;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblSchemeMasterController implements the CRUD actions for TblSchemeMaster model.
 */
class TblSchemeMasterController extends \app\controllers\ChildController {

    public $schemeCriteria, $schemeDocumentMapping;

    public function actionIndex() {
        $searchModel = new TblSchemeMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $dsearchModel = new TblSchemeCriteriaSearch();
        $dsearchModel->scheme_id = $id;
        $ddataProvider = $dsearchModel->search(Yii::$app->request->queryParams);
        $searchModel = new TblSchemeDocumentMappingSearch();
        $searchModel->scheme_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'ddataProvider' => $ddataProvider, 'dsearchModel' => $dsearchModel,
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new TblSchemeMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->start_date = !empty($this->model->start_date) ? date('Y-m-d', strtotime($this->model->start_date)) : '';
            $this->model->end_date = !empty($this->model->end_date) ? date('Y-m-d', strtotime($this->model->end_date)) : '';
            $saveModel = [];
            $schemeCriteria = new TblSchemeCriteria();
            $schemeCriteria->wef_date = $this->model->start_date;
            $schemeCriteria->min_pouring_day = $this->model->min_pouring_day;
            $schemeCriteria->min_pouring_qty = $this->model->min_pouring_qty;
            $schemeCriteria->scheme_value = $this->model->scheme_value;
            $schemeCriteria->union_code = $this->model->union_code;
            $saveModel[] = $this->model;
            $saveModel[] = $schemeCriteria;
            $auto_key_config = [];
            $auto_key_config['TblSchemeCriteria'][] = ['self_key' => 'scheme_id', 'parent_key' => 'scheme_id', 'parent_index' => 0];
            $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Scheme Master', 'create'], $auto_key_config);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->start_date = !empty($this->model->start_date) ? date('Y-m-d', strtotime($this->model->start_date)) : '';
            $this->model->end_date = !empty($this->model->end_date) ? date('Y-m-d', strtotime($this->model->end_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Finds the TblSchemeMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSchemeCriteria($id) {
        $schemeCriterias = new TblSchemeCriteria();
        $searchModel = new TblSchemeCriteriaSearch();
        $searchModel->scheme_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../welfarescheme/views/tbl-scheme-criteria/create', [
                    'model' => $schemeCriterias,
                    'id' => $id,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'schemeCriterias' => $this->schemeCriteria,
        ]);
    }

    public function actionApprovalStages($id) {
        $model = new TblSchemeApprovalStages();
        $model->scheme_id = $id;
        $user = new User();
        $user_list = $user->userList;

        $searchModel = new TblSchemeApprovalStagesSearch();
        $searchModel->scheme_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            $modelSave = [];
            $model->load(Yii::$app->request->post());
            $validate = TRUE;
            $error_msg = [];
            foreach ($model->user_code as $user_code) {
                $aprv_model = new TblSchemeApprovalStages();
                $aprv_model->attributes = $model->attributes;
                $aprv_model->user_code = $user_code;
                $aprv_model->old_approval_mode = ($aprv_model->approval_mode == 'strict') ? 'flexi' : 'strict';
                if (!$aprv_model->validate()) {
                    $validate = FALSE;
                    $error = $aprv_model->getErrors();
                    if (!empty($error['user_code'])) {
                        $u_detail = $aprv_model->userCode;
                        $error['user_code'][0] = Yii::t('app', 'User (' . $u_detail->name . '-' . $u_detail->department . ') has already been taken.');
                    }
                    if (!empty($error['approval_mode'])) {
                        $error_app_mode = TRUE;
                        $error['approval_mode'][0] = Yii::t('app', 'Approval Mode must be ' . Yii::$app->general->getStaticValue($aprv_model->old_approval_mode, 'approval_mode') . '.');
                    }
                    $error_msg[] = $error;
                }
                $modelSave[] = $aprv_model;
            }
            if ($validate) {
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Scheme Approval Stages', 'create']);
                if ($transaction == 'customRedirect') {
                    $model = new TblSchemeApprovalStages();
                    $model->scheme_id = $id;
                    // return $this->{$transaction}();
                }
            } else {
                $model->addErrors($error_msg);
            }
        }
        return $this->render('approval-stages', ['model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'user_list' => $user_list]);
    }

    public function actionDeleteApprovalStage() {
        $this->model = TblSchemeApprovalStages::findOne(Yii::$app->request->post('id'));
        $historyModel = new TblSchemeApprovalStagesHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionSchemeDocumentMapping($id) {
        $this->model = new TblSchemeDocumentMapping();
        $this->model->scheme_id = $id;
        $schemeModel = new TblSchemeMaster();
        $schemeData = $schemeModel->find()->where(['scheme_id' => $id])->one();
        $this->model->load(Yii::$app->request->queryParams);
        $searchModels = new TblSchemeDocumentMappingSearch();
        $searchModel = new TblDocumentMasterInfoSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->doc_id = $id;
        $searchModel->is_active = 1;
        $dataProvider = $searchModel->mappingsearch(Yii::$app->request->queryParams);
        $selectedArray = [];
        $selectedArray = $this->model->getExistingMapping();
        $mandateselectedArray = [];
        $mandateselectedArray = $this->model->getExistingMappingIsmandate();
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postArray = !empty($data['docId']) ? $data['docId'] : [];
            $postMendateArray = !empty($data['isMandate']) ? $data['isMandate'] : [];
            $master = [];
            $auto_inc = 1;
            $newAssignments = [];
            if (!empty($postArray)) {
                $newAssignments = $postArray;
            }
            $oldAssignments = [];
            if (!empty($selectedArray)) {
                $oldAssignments = array_keys($selectedArray);
            }
            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_values(array_diff($oldAssignments, $newAssignments));
            $toUpdate = array_diff($newAssignments, array_merge($toAssign, $toRevoke));

            $delete = [];
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_widget) {
                    $model = new TblSchemeDocumentMapping();
                    $model->doc_id = $revoke_widget;
                    $model->scheme_id = $id;
                    $model->union_code = $schemeData->union_code;
                    $record = $model->getExistMappedControl();
                    $historyModel = new TblSchemeDocumentMappingHistory();
                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }
            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_widget) {
                    $model = new TblSchemeDocumentMapping();
                    $model->doc_id = $Assign_widget;
                    $model->scheme_id = $id;
                    $model->is_mandate = !empty($postMendateArray) && in_array($Assign_widget, $postMendateArray) ? 1 : 0;
                    $model->union_code = $schemeData->union_code;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            if (!empty($toUpdate)) {
                foreach ($toUpdate as $update_widget) {
                    $model = new TblSchemeDocumentMapping();
                    $model->doc_id = $update_widget;
                    $model->scheme_id = $id;
                    $model->is_mandate = !empty($postMendateArray) && in_array($update_widget, $postMendateArray) ? 1 : 0;
                    $record = $model->getExistMappedControl();
                    if (!empty($record) && ($model->is_mandate != $record->is_mandate)) {
                        $historyModel = new TblSchemeDocumentMappingHistory();
                        Yii::$app->operation->history($record, $historyModel, 'UPDATE');
                        $master[] = $historyModel;
                        $record->is_mandate = $model->is_mandate;
                        $master[] = $record;
                    }
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Control Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];

            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('../../../welfarescheme/views/tbl-scheme-document-mapping/create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectedArray' => $selectedArray,
                    'mandateselectedArray' => $mandateselectedArray,
        ]);
    }

}
