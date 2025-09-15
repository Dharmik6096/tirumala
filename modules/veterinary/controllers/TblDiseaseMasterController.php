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
    
    public function actionMapRouteSource($id) {
        $model = new TblDiseaseSymptomMapping();
        $Symtom = $this->findModel($id);
        $values = $model->getSymptoms($Symtom);
        $dest = [];
        $searchModel = new TblDiseaseSymptomMappingSearch();
        $searchModel->disease_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        foreach ($values['results'] as $value) {
            $dest[$value['symptom_id'] . '-' . $value['symptom_name']] = $value['symptom_id'] . ' - ' . $value['symptom_name'];
        }
        if (Yii::$app->request->post()) {
            echo '<pre>';
            print_r(Yii::$app->request->post('TblDiseaseSymptomMapping')['symptom_id']);
            die;
            $applicable_code = Yii::$app->request->post('TblDiseaseSymptomMapping')['symptom_id'];
            $user_code = Yii::$app->request->post('TblOrganizationLatLongApplicability')['user_code'];
            if (empty($applicable_code)) {
                $model->addError('applicable_code', 'Please select at least one Source.');
            } else {
                $postData = array_filter($applicable_code);
                $mapping = [];
                $saveModel = [];
                $error_msg = [];
                $validatefalse = 0;
                foreach ($postData as $data) {
                    $modelnew = new TblOrganizationLatLongApplicability();
                    $model->scenario = 'saveLatlongApplicability';
                    $d = explode('-', $data);
                    $modelnew->applicable_code = $d[0];
                    $modelnew->organization_latlong_code = $d[1];
                    $modelnew->applicable_for = $d[2];
                    $modelnew->user_code = $user_code;
                    $modelnew->union_code = $modelApplicability->union_code;
                    if ($modelnew->validate()) {
                        $saveModel[] = $modelnew;
                        
                    }else{
                        $validatefalse++;
                    }
                    
                }
                if($validatefalse == 0){
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Org Latlong Mapping', 'create']);
                    if ($transaction) {
                        Yii::$app->display->message(true, 'Organization Latlong Mapping', 'create');
                        return $this->redirect(['map-route-source','id' => $id]);
                    }
                }
                
            }
        }

        return $this->render('_map_route_source', [
                    'model' => $model, 
                    'Symtom' => $Symtom, 
                    'destinations' => $dest,
                   'selected' => $values['selected'],
                    'defaultValue' => '', 
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

}
