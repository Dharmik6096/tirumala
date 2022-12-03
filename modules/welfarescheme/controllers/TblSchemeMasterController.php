<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\welfarescheme\models\TblSchemeMasterHistory;
use app\modules\welfarescheme\models\TblSchemeMasterSearch;
use app\modules\welfarescheme\models\TblSchemeCriteria;
use app\modules\welfarescheme\models\TblSchemeCriteriaSearch;
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

    public $schemeCriteria;

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
        return $this->render('view', [
                    'model' => $this->findModel($id),
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
            $mapList = [];
            $schemeCriteria = new TblSchemeCriteria();
            $schemeCriteria->wef_date = $this->model->start_date;
            $schemeCriteria->min_pouring_day = $this->model->min_pouring_day;
            $schemeCriteria->min_pouring_qty = $this->model->min_pouring_qty;
            $schemeCriteria->scheme_value = $this->model->scheme_value;
            array_push($mapList, $schemeCriteria);
            $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['Scheme Master', 'create']);
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../welfarescheme/views/tbl-scheme-criteria/create', [
                    'model' => $schemeCriterias,
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
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Scheme Approval Stages', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
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

}
