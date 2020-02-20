<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblCustomerMasterSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblCustomerMasterHistory;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcs;

/**
 * TblCustomerMasterController implements the CRUD actions for TblCustomerMaster model.
 */
class TblCustomerMasterController extends \app\controllers\ChildController {

    public $freeAccessActions = ['customer-type', 'customer-code-list'];

    /**
     * Lists all TblCustomerMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCustomerMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCustomerMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblCustomerMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCustomerMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->customer_code = $this->model->getCode();
//            $this->model->customer_code_ex = $this->model->getCodeEx();
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Customer Master', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblCustomerMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $x_col1 = explode('#', $this->model->x_col1);
        if (isset($x_col1)) {
            if (isset($x_col1[0]) && isset($x_col1[1])) {
                $this->model->same_milk_type = $x_col1[0];
                $this->model->diff_milk_type = $x_col1[1];
            }
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblCustomerMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Customer Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCustomerMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCustomerMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCustomerMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCustomerMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCustomerType() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $routes = new TblCustomerMaster();
                $mcc = $parents[0];
                $bmc = !empty($parents[1]) ? $parents[1] : NULL;
                $data = $routes->customerType($mcc, $bmc);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionCustomerCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                if (strtolower($parents[1]) == 'dcs') {
                    $mccs = new TblDcs();
                    $data = $mccs->getBMCDCSList($parents[0], 'TRUE');
                } else {
                    $model = new TblCustomerMaster();
                    $data = $model->getCustomerCodeList($parents[0], $parents[1]);
                }
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
