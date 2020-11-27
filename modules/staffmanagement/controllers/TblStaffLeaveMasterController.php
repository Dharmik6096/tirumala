<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffLeaveMaster;
use app\modules\staffmanagement\models\TblStaffLeaveMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\staffmanagement\models\TblStaffLeaveMasterHistory;
use yii\helpers\Json;

/**
 * TblStaffLeaveMasterController implements the CRUD actions for TblStaffLeaveMaster model.
 */
class TblStaffLeaveMasterController extends \app\controllers\ChildController {

    public $freeAccessActions = ['leave-type'];

    /**
     * Lists all TblStaffLeaveMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffLeaveMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffLeaveMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblStaffLeaveMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblStaffLeaveMaster();
        $this->viewFile = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->staff_leave_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->is_default = $this->model->leave_type == 4 ? 1 : 0;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Staff Leave Master', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblStaffLeaveMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblStaffLeaveMasterHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $model->load(Yii::$app->request->post());
            $model->is_default = $model->leave_type == 4 ? 1 : 0;
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Leave Master', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblStaffLeaveMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffLeaveMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStaffLeaveMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffLeaveMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLeaveType() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $config = new TblStaffLeaveMaster();
                $data = $config->getLeaveType($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
