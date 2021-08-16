<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCluster;
use app\modules\organisation\models\TblClusterHistory;
use app\modules\organisation\models\TblClusterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;

/**
 * TblClusterController implements the CRUD actions for TblCluster model.
 */
class TblClusterController extends \app\controllers\ChildController {

    public $contactDetails;

    /**
     * Lists all TblCluster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblClusterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCluster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'cluster';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblCluster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblCluster();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'cluster-create';
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->cluster_code = $this->model->getCode();
            $this->model->name = ucwords($this->model->name);

            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('cluster', $this->model->cluster_code);
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'name', $this->model->name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], [$this->contactDetails], ['Cluster', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('create', ['model' => $this->model,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    /**
     * Updates an existing TblCluster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {

            $historyModel = new TblClusterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->model->name = ucwords($this->model->name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'name', $_POST['TblCluster']['name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Cluster', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCluster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblClusterHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['cluster_code', 'cluster']);
    }

    /**
     * Finds the TblCluster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCluster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCluster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'cluster';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'cluster',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

}
