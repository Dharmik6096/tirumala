<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblArea;
use app\modules\geo\models\TblAreaHistory;
use app\modules\geo\models\TblAreaSearch;
use yii\web\NotFoundHttpException;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\controllers\ChildController;

/**
 * TblAreaController implements the CRUD actions for TblArea model.
 */
class TblAreaController extends ChildController {

    public $contactDetails;

    /**
     * Lists all TblArea models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblArea model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'area';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblArea();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'area-create';
        $validate = 1;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->area_name = ucwords($this->model->area_name);

            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('area', $this->model->area_code);
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'area_name', $this->model->area_name);
            if ($validate == 1) {
                $auto_key_config['TblContactDetails'][] = ['self_key' => 'module_code', 'parent_key' => 'area_code', 'parent_index' => 0];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey([$this->model, $this->contactDetails], ['Area Detail', 'create'], $auto_key_config);
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
     * Updates an existing TblArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {

            $historyModel = new TblAreaHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->model->area_name = ucwords($this->model->area_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'area_name', $_POST['TblArea']['area_name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Area', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblAreaHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['area_code', 'area']);
    }

    /**
     * Finds the TblArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblArea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'area';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'area',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

}
