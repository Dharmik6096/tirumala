<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblTankerRateApplicability;
use app\modules\tankermovement\models\TblTankerRateApplicabilitySearch;
use app\modules\tankermovement\models\TblTankerRate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tankermovement\models\TblTankerRateApplicabilityHistory;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * TblTankerRateApplicabilityController implements the CRUD actions for TblTankerRateApplicability model.
 */
class TblTankerRateApplicabilityController extends \app\controllers\ChildController
{
    public $purchaseRate;
    public $routes;

    /**
     * Lists all TblTankerRateApplicability models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblTankerRateApplicabilitySearch();
        $dataProvider = $searchModel->downloadSearch(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTankerRateApplicability model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTankerRateApplicability model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $pr = new TblTankerRate();
        $purchaseRate = $pr->getRecord($id);
        $this->purchaseRate=$purchaseRate;
        $this->model = new TblTankerRateApplicability();
        $this->viewFile = 'create';
        $this->model->union_code=$purchaseRate->union_code;
        $this->model->purchase_rate_code=$id;
        $this->model->wef_date = $purchaseRate->wef_date;
        $this->model->shift_code = $this->routes['shiftCode'];
        if ($this->model->load(Yii::$app->request->post())) {
            if($this->model->validate()){
                echo  $this->routes['shiftCode'];exit;
        
                $relationalModel = new TblDcs();
                $oldModel = new TblTankerRateApplicability();
                $dataold = $oldModel->find()->where(['purchase_rate_code' => $id])->all();
                $returnedArray = \yii\helpers\ArrayHelper::map($dataold, 'dcs_code', 'dcs_code');
                
                $toRevoke = array_diff($returnedArray, $this->model->dcs_code);
                $toAssign = array_diff($this->model->dcs_code, $returnedArray);
                $mappingList = [];
                
                foreach ($toRevoke as $value) {
                    $milkModel = TblTankerRateApplicability::find()->where(['dcs_code' => $value, 'purchase_rate_code' => $id])->one();
                    $milkHistory = new TblTankerRateApplicabilityHistory();
                    Yii::$app->operation->history($milkModel, $milkHistory, DELETE);
                    array_push($mappingList, $milkHistory);
                    array_push($mappingList, $milkModel);
//                    echo 'revike = '.$value.' code = '.$milkModel->rate_app_code.' = '.$milkModel->is_delete.'<br>';
                }
                $code = $this->model->getCode();
                foreach ($toAssign as $value) {
                    $milkModel = new TblTankerRateApplicability();
                    $milkModel->rate_app_code = $code;
                    $milkModel->dcs_code= $value;
                    $milkModel->purchase_rate_code=$this->model->purchase_rate_code;
                    $milkModel->union_code = $this->model->union_code;
                    $milkModel->is_active = 1;
                    $milkModel->wef_date = Yii::$app->formatter->asDate($this->model->wef_date,DATE_FORMAT);
                    $milkModel->shift_code = $this->model->shift_code;
                    $check = $milkModel->checkDuplicate();
                    if($check==1){
                         $this->model->addError('wef_date',$milkModel->wef_date.' date already taken by dcs.');
                         return $this->customRender();
                     } 
                     $code = str_pad($code+1,2,'0',STR_PAD_LEFT);
                    array_push($mappingList, $milkModel);
                    
                }
                $transaction = $this->generalModel->saveTransaction($mappingList, ['purchase rate applicability', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                 }
            }
        } 
        return $this->customRender();
    }

    protected function customRender() {
        $searchModel = new TblTankerRateApplicabilitySearch();
        $searchModel->purchase_rate_code=$this->model->purchase_rate_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $selected=  $this->model->getDcs();
        if(!empty($this->model->union_code))
        {
            $dcs_list=  $this->loadDcs($this->model->union_code);
        }
        else
        {
            $dcs_list=[];
        }
        return $this->render('create', [
            'model' => $this->model,'purchaseRate' => $this->purchaseRate,
            'dcs_list'=>$dcs_list,'selected'=>$selected,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function customRedirect() {
       
        return $this->redirect(['tbl-purchase-rate/index']);
    }

    /**
     * Updates an existing TblTankerRateApplicability model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rate_app_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblTankerRateApplicability model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblTankerRateApplicability model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblTankerRateApplicability the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblTankerRateApplicability::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function loadDcs($union_code)
    {
        $unionModel=new \app\modules\organisation\models\TblUnions();
        $unionModel=$unionModel->findOne($union_code);
        $dcsList=$unionModel->tblDcs;
        $dcsList=  ArrayHelper::map($dcsList, 'dcs_code', 'dcs_name');
        return $dcsList;
    }
}
