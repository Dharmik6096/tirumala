<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblPlantSearch;
use app\modules\organisation\models\TblPlantHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\organisation\models\TblPlantProductGroupDetails;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblMccPlantSearch;
use app\modules\organisation\models\TblDcsBmc;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblPlantController implements the CRUD actions for TblPlant model.
 */
class TblPlantController extends \app\controllers\ChildController
{
     public $contactDetails;
     public $freeAccessActions = ['plant-list'];
    /**
     * Lists all TblPlant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblPlantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPlant model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
//        $csearchModel = new TblContactDetailsSearch();
//        $csearchModel->module_name='society';
//        $csearchModel->module_code=$id;
//        $cdataProvider= $csearchModel->search(Yii::$app->request->queryParams);
//        return $this->render('view', [
//            'model' => $this->findModel($id),'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
//        ]);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name='plant';
        $csearchModel->module_code=$id;        
        $cdataProvider= $csearchModel->search(Yii::$app->request->queryParams);
        
        $mccsearchModel = new TblMccPlantSearch();
        $mccsearchModel->plant_code = $id;
        $mccdataProvider = $mccsearchModel->mccSearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
                    'mccdataProvider' => $mccdataProvider, 'mccsearchModel' => $mccsearchModel
        ]);
    }

    /**
     * Creates a new TblPlant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblPlant();
        $this->viewFile = 'create';
        $this->contactDetails=new TblContactDetails();
        $this->model->valid_from =  date('Y-m-d');
        $this->contactDetails->scenario='additional';
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            
            $this->setModel($this->model);
            $this->model->plant_code = $this->model->getCode();
            $this->model->name = ucwords($this->model->name);
            
            $mccModel=new TblMccPlant();
            $mccModel->plant_code=$this->model->plant_code;
            $mccModel->mcc_plant_code=$mccModel->getCode();
            $mccModel->name= $this->model->name;
            $mccModel->local_name= $this->model->local_name;
            $mccModel->capacity= $this->model->capacity;
            $mccModel->is_active= $this->model->is_active;
            $mccModel->state_code= $this->model->state_code;
            $mccModel->district_code= $this->model->district_code;
            $mccModel->sub_district_code= $this->model->sub_district_code;
            $mccModel->village_code= $this->model->village_code;
            $mccModel->hamlet_code= $this->model->hamlet_code;
            $mccModel->union_code= $this->model->union_code;
            $mccModel->valid_from =  $this->model->valid_from;
            $mccModel->is_plant=1; 

            $bmcModel=new TblDcsBmc();
            $bmcModel->scenario='from_mcc';
            $bmcModel->mcc_code=$mccModel->mcc_plant_code;
            $bmcModel->bmc_code=$bmcModel->getCode();
            $bmcModel->bmc_name=  $mccModel->name;
            $bmcModel->local_name=  $mccModel->local_name;
            $bmcModel->capacity=  $mccModel->capacity;
            $bmcModel->is_active= $mccModel->is_active;
            $bmcModel->state_code= $mccModel->state_code;
            $bmcModel->district_code= $mccModel->district_code;
            $bmcModel->sub_district_code= $mccModel->sub_district_code;
            $bmcModel->village_code= $mccModel->village_code;
            $bmcModel->hamlet_code= $mccModel->hamlet_code;
            $bmcModel->union_code= $mccModel->union_code;
            $bmcModel->valid_from =  $this->model->valid_from;
            $bmcModel->is_mcc=1;
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('plant', $this->model->plant_code);
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'name', $this->model->name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model],[$mccModel,$bmcModel,$this->contactDetails], ['plant', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblPlant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {
            
            $historyModel = new TblPlantHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $this->model->name = ucwords($this->model->name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'name', $_POST['TblPlant']['name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['plant', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblPlant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        //echo Yii::$app->request->post('id');exit;
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_plant','tbl_plant_product_group_details', 'tbl_plant_product_group_details_history', Yii::$app->request->post('id'), 'plant_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblPlantHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel],['TblPlantProductGroupDetails','TblPlantProductGroupDetailsHistory', 'TblContactDetails', 'TblContactDetailsHistory'],['plant_code','plant']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }
    
    public function actionContactDetails($id)
    {
        $contactDetails=new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name='plant';
        $searchModel->module_code=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
            'model' => $contactDetails,
            'id'=>$id,
            'module'=>'plant',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        
    }
    
    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->plant_code]);
    }
    
    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
            'contactDetails'=>$this->contactDetails
            ]);
    }

    /**
     * Finds the TblPlant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblPlant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblPlant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function setModel() {
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);        
    }
    
    public function actionMapProductGroups($id) {
        $model = new TblPlantProductGroupDetails();
        $modelPlant = $this->findModel($id);
        $values = $model->getProductGroups($modelPlant);
        if (Yii::$app->request->post()) {

            $plant_code = Yii::$app->request->post('TblPlantProductGroupDetails')['plant_code'];
            $product_group_code = Yii::$app->request->post('TblPlantProductGroupDetails')['plant_product_group_code'];

            $postData = array_filter($product_group_code);
            if (empty($postData)) {
                $model->addError('plant_code', 'Please select at lease one Product Group.');
                $district_code = [];
                return $this->render('_map_product_group', [
                            'model' => $model, 'product_groups' => $values['product_groups'], 'selected' => $district_code, 'modelPlant' => $modelPlant, 'defaultValue' =>'' //$modelUnion->district_code
                ]);
            }


            $data = $model->find()->where(['plant_code' => $id, 'is_active' => 1])->all();

            $returnedArray = \yii\helpers\ArrayHelper::map($data, 'plant_product_group_code', 'plant_product_group_code');

            $districts = Yii::$app->general->array_flatten($values['product_groups'], 1);
            $postData = Yii::$app->general->array_flatten($postData);
            $oldAssignments = array_keys($returnedArray);
            $newAssignments = array_intersect(array_flip($districts), $postData);

            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_diff($oldAssignments, $newAssignments);

            $record = $this->generalModel->mappingTransaction($toRevoke, $toAssign, ['TblPlantProductGroupDetails', 'TblPlantProductGroupDetailsHistory'], ['plant_code', 'plant_product_group_code'], $id);

            if ($record) {
                Yii::$app->display->message(true, 'plant product group mapping', 'edit');
                return $this->redirect(['index']);
            }
        }
        return $this->render('_map_product_group', [
                    'model' => $model, 'product_groups' => $values['product_groups'], 'selected' => $values['selected'], 'modelPlant' => $modelPlant, 'defaultValue' => ''//$modelUnion->district_code
        ]);
    }
    
    public function actionPlantList(){        
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $transporter_code = $parents[0];
                $plants = new TblPlant();
                $out = $plants->rlsPlant($parents[0]);

                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    
    }
}
