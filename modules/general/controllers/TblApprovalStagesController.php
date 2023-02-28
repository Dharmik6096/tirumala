<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblApprovalStages;
use app\modules\general\models\TblApprovalStagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\general\models\TblApprovalStagesDetailSearch;
use app\modules\general\models\TblApprovalStagesDetail;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * TblApprovalStagesController implements the CRUD actions for TblApprovalStages model.
 */
class TblApprovalStagesController extends \app\controllers\ChildController {

    public $freeAccessActions = ['level-list'];

    /**
     * Lists all TblApprovalStages models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblApprovalStagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblApprovalStages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblApprovalStages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblApprovalStages();
        $searchModel = new TblApprovalStagesDetailSearch();
        $searchModel->attributes = Yii::$app->request->get('TblApprovalStages');
        $dataProvider = $searchModel->createsearch([]);
        $txModel = new TblApprovalStagesDetail();

        $dataProvider->sort = false;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Approval Stages';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $txModel->load(Yii::$app->request->post());
            if (empty($this->model->approval_stages_code)) {
                $this->model->approval_stages_code = Yii::$app->general->getCodeAutoIncrement($this->model);
                $modelSave[] = $this->model;
            }
            $txModel->approval_stages_code = $this->model->approval_stages_code;
            $existLevele = $txModel->existingLevel();
            $txModel->level_priority = !empty($existLevele) ? $existLevele->level_priority + 0.1 : $txModel->level . '.' . '1';

            $modelSave[] = $txModel;
            if ($this->model->validate() && $txModel->validate()) {

                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->approval_stages_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblApprovalStages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $searchModel = new TblApprovalStagesDetailSearch();
        $searchModel->attributes = Yii::$app->request->get('TblApprovalStages');
        $searchModel->approval_stages_code = $id;
        $dataProvider = $searchModel->createsearch([]);
        $txModel = new TblApprovalStagesDetail();

        $dataProvider->sort = false;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Approval Stages';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $txModel->load(Yii::$app->request->post());
            $txModel->approval_stages_code = $this->model->approval_stages_code;
            $existLevele = $txModel->existingLevel();
            $txModel->level_priority = !empty($existLevele) ? $existLevele->level_priority + 0.1 : $txModel->level . '.' . '1';

            $modelSave[] = $txModel;
            if ($this->model->validate() && $txModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->approval_stages_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('update', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
            ]);
        }
        return $this->render('update', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Deletes an existing TblApprovalStages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblApprovalStages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblApprovalStages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblApprovalStages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblApprovalStagesDetailSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblApprovalStages'));
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionLevelList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $approval = new TblApprovalStagesDetail();
                $data = $approval->getLevelList($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
