<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblMasterTransfer;
use app\modules\organisation\models\TblMasterTransferSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\globalmaster\models\TblTransferType;
use yii\helpers\Json;
use app\modules\organisation\models\TblMasterTransferHistory;
use yii\web\Response;
use app\modules\dcsoperation\models\TblMemberSearch;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberHistory;

/**
 * TblMasterTransferController implements the CRUD actions for TblMasterTransfer model.
 */
class TblMasterTransferController extends \app\controllers\ChildController {
    
    public $freeAccessActions = ['dcs-list'];

    /**
     * Lists all TblMasterTransfer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMasterTransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMasterTransfer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMasterTransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMasterTransfer();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
          
            $this->model->scenario = $this->model->master_type;
            if ($this->model->validate()) {
                $this->model->wef_date = !empty($this->model->wef_date) ? Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT) : '';
                $this->model->old_route_code = $this->model->master_type == 'CUSTOMER' ? $this->model->routeCode->route_code : $this->model->oldDcsCode->route_code;
                $modelList = [];
                if ($this->model->master_type == 'FARMER') {
                    foreach ($this->model->old_member_code as $key => $member_code) {
                        if ($member_code == 'multiselect-all') {
                            continue;
                        }
                        $model = new TblMasterTransfer();
                        $model->attributes = $this->model->attributes;
                        $model->old_member_code = $member_code;
                        $modelList[] = $model;
                    }
                } else {
                    $modelList[] = $this->model;
                }
                $transaction = $this->generalModel->saveTransaction($modelList, ['Transfer', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {
                $this->model->scenario = 'default';
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMasterTransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->master_transfer_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMasterTransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblMasterTransferHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMasterTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMasterTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMasterTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTransferTypeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $problems = new TblTransferType();
                $data = $problems->getTransferTypeList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionRollbackMember() {
        $searchModel = new TblMemberSearch();
        $dataProvider = $searchModel->rollbacksearch(Yii::$app->request->queryParams);
        $searchModel->grid_filter = FALSE;
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $rollbackdata = Yii::$app->request->post('selection');
                foreach ($rollbackdata as $key => $value) {
                    $mcodes = explode('-', $value);
                    $member_code = $mcodes[0];
                    $old_member_code = $mcodes[1];
                    $model = TblMember::findOne($member_code);
                    $oldmodel = TblMember::findOne($old_member_code);
                    $historyModel = new TblMemberHistory();
                    Yii::$app->operation->history($model, $historyModel, 'ROLLBACK');
                    $saveModel[] = $historyModel;
                    $historyModel = new TblMemberHistory();
                    Yii::$app->operation->history($oldmodel, $historyModel, 'ROLLBACK');
                    $saveModel[] = $historyModel;
                    $model->is_active = 0;
                    $model->tag_1 = $oldmodel->tag_1 = 'Y';
                    $model->tag_2 = $oldmodel->tag_2 = NULL;
                    $oldmodel->is_active = 1;
                    $oldmodel->old_member_code = NULL;
                    $oldmodel->old_dcs_code = NULL;
                    $model->scenario = $oldmodel->scenario = 'rollback';
                    $saveModel[] = $model;
                    $saveModel[] = $oldmodel;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Roll Back Farmer', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('rollback_member', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDcsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $transafer = new TblMasterTransfer();
                $rls = isset($parents[1]) && $parents[1] == 'false' ? 'FALSE' : 'TRUE';
                $data = $transafer->getDCSList($parents[0], $rls);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
