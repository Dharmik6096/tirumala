<?php

namespace app\modules\veterinary\controllers;

use Yii;
use app\modules\veterinary\models\TblDiseaseMaster;
use app\modules\veterinary\models\TblDiseaseMasterHistory;
use app\modules\veterinary\models\TblDiseaseMasterSearch;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use app\modules\veterinary\models\TblDiseaseSymptomMapping;
use app\modules\veterinary\models\TblDiseaseSymptomMappingSearch;
use app\modules\veterinary\models\TblDiseaseSymptomMappingHistory;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * TblDiseaseMasterController implements the CRUD actions for TblDiseaseMaster model.
 */
class TblDiseaseMasterController extends ChildController {

    /**
     * Lists all TblDiseaseMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDiseaseMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDiseaseMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDiseaseMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDiseaseMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Disease', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDiseaseMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblDiseaseMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Disease', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblDiseaseMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDiseaseMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDiseaseMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMapSymptom($id) {
        $model = new TblDiseaseSymptomMapping();
        $diseaseMaster = $this->findModel($id);
        $values = $model->getSymptoms($diseaseMaster);
        $symtom = [];
        $searchModel = new TblDiseaseSymptomMappingSearch();
        $searchModel->disease_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        foreach ($values['results'] as $value) {
            $symtom[$value['symptom_id'] . '-' . $value['symptom_name']] = $value['symptom_id'] . ' - ' . $value['symptom_name'];
        }
        if (Yii::$app->request->post()) {
            $symptom_id = Yii::$app->request->post('TblDiseaseSymptomMapping')['symptom_id'];
            $disease_id = Yii::$app->request->post('TblDiseaseSymptomMapping')['disease_id'];
            if (empty($symptom_id)) {
                $model->addError('applicable_code', 'Please select at least one Symptom.');
            } else {
                $postData = array_filter($symptom_id);
                $saveModel = [];
                $validatefalse = 0;
                foreach ($postData as $data) {
                    $modelnew = new TblDiseaseSymptomMapping();
                    $d = explode('-', $data);
                    $modelnew->symptom_id = $d[0];
                    $modelnew->disease_id = $disease_id;
                    if ($modelnew->validate()) {
                        $saveModel[] = $modelnew;
                    } else {
                        $validatefalse++;
                    }
                }
                if ($validatefalse == 0) {
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Symptom Mapping', 'create']);
                    if ($transaction) {
                        Yii::$app->display->message(true, 'Symptom Mapping', 'create');
                        return $this->redirect(['map-symptom', 'id' => $id]);
                    }
                }
            }
        }

        return $this->render('_map_symptom', [
                    'model' => $model,
                    'diseaseMaster' => $diseaseMaster,
                    'symtom' => $symtom,
                    'selected' => $values['selected'],
                    'defaultValue' => '',
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteSource() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = TblDiseaseSymptomMapping::findOne(Yii::$app->request->post('id'));
        if (!empty($this->model)) {
            $historyModel = new TblDiseaseSymptomMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $saveModel[] = $historyModel;
            $deleteModel[] = $this->model;
            $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Mapped Symptom', 'delete']);
            if ($transaction == 'customRedirect') {
                $this->redirect(['map-symptom', 'id' => $this->model->disease_id]);
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }
    
    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblDiseaseMasterHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 0;
        if ($this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Disease', 'edit']);
            if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Disease deactivated successfully.']);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Could not deactivate. Please try again.']);
            }
        } else {
            $msg = '';
            foreach ($this->model->getErrors() as $errorkey => $value) {
                $msg .= $value[0] . '<br/>';
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $msg]);
            return $this->redirect(\yii\helpers\Url::previous());
        }

        return $this->redirect(Url::previous());
    }

}
