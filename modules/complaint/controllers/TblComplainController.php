<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplain;
use app\modules\complaint\models\TblComplainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\complaint\models\TblComplainProblem;
use yii\helpers\Json;
use app\modules\complaint\models\TblComplainActivity;
use yii\web\Response;
use app\modules\complaint\models\TblComplainType;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\complaint\models\TblComplainHistory;
use app\modules\complaint\models\TblComplainActivityHistory;

/**
 * TblComplainController implements the CRUD actions for TblComplain model.
 */
class TblComplainController extends \app\controllers\ChildController {

    public $freeAccessActions = ['problem-list', 'get-asset'];

    /**
     * Lists all TblComplain models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblComplainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblComplain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblComplain();
        $this->model->scenario = 'portal_create_complaint';
        $complaint_activity_model = new TblComplainActivity();
        $this->viewFile = 'create';
        $this->model->complain_datetime = date('Y-m-d H:i:s');
        $this->model->complain_status = 'CREATED'; //create
        $this->model->entry_type = 'PORTAL';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            if ($this->model->validate()) {
                $this->setComplaintActivityModel($complaint_activity_model);
                $auto_key_config['TblComplainActivity'][] = ['self_key' => 'complain_code', 'parent_key' => 'complain_code', 'parent_index' => 0];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey([$this->model, $complaint_activity_model], ['Complain', 'create'], $auto_key_config);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblComplain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblComplainHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Complain', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblComplain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $saveModel = [];
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblComplainHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $saveModel[] = $this->model;
        $saveModel[] = $historyModel;

        $model = TblComplainActivity::find()->where(['complain_code' => Yii::$app->request->post('id')])->one();
        $activityHistoryModel = new TblComplainActivityHistory();
        Yii::$app->operation->history($model, $activityHistoryModel, DELETE);
        $saveModel[] = $model;
        $saveModel[] = $activityHistoryModel;

        $record = $this->generalModel->deleteTransaction($saveModel);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblComplain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblComplain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setModel() {
//        $this->model->complain_datetime = ($this->model->complain_datetime == '') ? null : Yii::$app->formatter->asDate($this->model->complain_datetime, DATE_FORMAT);
        $this->model->resolved_datetime = ($this->model->resolved_datetime == '') ? null : Yii::$app->formatter->asDate($this->model->resolved_datetime, DATE_FORMAT);
    }

    private function setComplaintActivityModel($complaint_activity_model) {
        $complaint_activity_model->complain_code = $this->model->complain_code;
        $complaint_activity_model->activity_type = $this->model->complain_status;
        $complaint_activity_model->remarks = $this->model->remarks;
        $complaint_activity_model->entry_type = 'PORTAL';
    }

    public function actionProblemList() {
        $complains = [];
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $complain_for = TblComplainType::find()->select('complain_for')->where(['complain_type_code' => $parents[0], 'is_active' => 1])->one();
                if (!empty($complain_for)) {
                    if ($complain_for->complain_for == 'general_complain') {
                        $complains = TblComplainProblem::find()->where(['or', ['asset_code' => null], ['asset_code' => '']])->all();
                    } else {
                        $complains = TblComplainProblem::find()->where(['is not', 'asset_code', null])->andWhere(['!=', 'asset_code', ''])->all();
                    }
                    foreach ($complains as $key => $val) {
                        $out[] = array('id' => $val->complain_problem_code, 'name' => $val->problem_desc);
                    }
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
        return;
    }

    public function actionGetComplainFor() {
        $complain_type = Yii::$app->request->post()['complain_type'];
        $complain_for = TblComplainType::find()->select('complain_for')->where(['complain_type_code' => $complain_type, 'is_active' => 1])->one();
        $record = ['status' => 'success', 'msg' => $complain_for];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGetSrNumber() {
        $asset_code = Yii::$app->request->post()['asset_code'];
        $sno = TblAssetMaster::getSrNo($asset_code);
        $record = ['status' => 'success', 'msg' => $sno];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
