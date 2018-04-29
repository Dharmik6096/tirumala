<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblHeadLoad;
use app\modules\dcsoperation\models\TblHeadLoadSearch;
use app\modules\dcsoperation\models\TblHeadLoadHistory;
use app\modules\dcsoperation\models\TblHeadLoadApplicability;
use app\modules\dcsoperation\models\TblHeadLoadApplicabilityHistory;
use app\modules\dcsoperation\models\TblHeadLoadTransactionSearch;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblRoutes;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblHeadLoadController implements the CRUD actions for TblHeadLoad model.
 */
class TblHeadLoadController extends \app\controllers\ChildController
{

    /**
     * Lists all TblHeadLoad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblHeadLoadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblHeadLoad model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new TblHeadLoadTransactionSearch();
        $searchModel->head_load_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view', [
            'model' => $this->findModel($id),'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblHeadLoad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblHeadLoad();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            
            $this->model->head_load_code = $this->model->getCode();
            $this->model->union_code = Yii::$app->session->get('organizations_code');
//            $this->model->union_code = '001';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Head Load', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    public function customRedirect() {
       if($this->model->is_active==1)
        return $this->redirect(['tbl-head-load-transaction/create', 'id' => $this->model->head_load_code]);
       else
        return $this->redirect(['tbl-head-load/view', 'id' => $this->model->head_load_code]);
    }

    /**
     * Updates an existing TblHeadLoad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblHeadLoadHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Head Load', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
            
        } 
        return $this->customRender();
    }

    /**
     * Deletes an existing TblHeadLoad model.
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
     * Finds the TblHeadLoad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblHeadLoad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblHeadLoad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionMapDcs($id){
        
        $model = new TblHeadLoadApplicability();
        $headLoad = $this->findModel($id);
        
       
        $data = $model->getHeadLoadApplicability($id,$headLoad->union_code);
        $model->organization = $data['orgFlag'];
        $model->wef_date = $data['wefDate'];
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $org = ($model->organization==0)?'dcs_code':'sub_center_code';
                
                if($model->organization==0){
                    $org = 'dcs_code';
                    $crossOrg = 'sub_center_code';
                    $relationalModel = new TblDcs();
                }else{
                    $org = 'sub_center_code';
                    $crossOrg = 'dcs_code';
                    $relationalModel = new TblSubCenter();
                }
                
                $oldModel = new TblHeadLoadApplicability();
                $dataold = $oldModel->find()->where(['head_load_code' => $id])->all();
                
                foreach ($dataold as $d) {
                    Yii::$app->operation->history($d, new TblHeadLoadApplicabilityHistory(), 'Edit', FALSE);
                }
                $oldModel->deleteAll(['head_load_code' => $id]);
                
                foreach ($model->dcs_code as $dcs){
                     $modelNew = new TblHeadLoadApplicability();
                     $modelNew->code = $model->getCode();
                     $modelNew->$org = $dcs;
                     $modelNew->head_load_code=$id;
                     $modelNew->wef_date = Yii::$app->formatter->asDate($model->wef_date,DATE_FORMAT);
                     $check = $modelNew->checkDuplicate();
                     
                     if($check==1){
                         $model->addError('wef_date',$modelNew->wef_date.' date already taken by dcs.');
                         
                         return $this->render('_map_dcs', [
                            'headLoad' => $headLoad,'model'=>$model,'routes'=>$data['routes'],'selectedRoutes'=>$data['selectedRoutes'],
                            'selectedOrganization'=>$data['selectedOrganization'],'selectedAllOrg'=>$data['selectedAllOrg'],
                        ]);
                     }
                     
                    $relationalModel->$org = $dcs;
                    $relationalCode = $relationalModel->getMainSubCenter();
                    $modelNew->$crossOrg = ($relationalCode)?$relationalCode->$crossOrg:null;
//                     
                     Yii::$app->operation->defaults($modelNew, UPDATE);
                     if($modelNew->save(false)){
                         $sentbox = new \app\models\TblSentbox();
                        if ($sentbox->setSentbox($modelNew, UPDATE)) {
                            $modelNew->flg_sentbox_entry = 'Y';
                            $modelNew->save();
                        }
                     }
                }
                Yii::$app->display->message(true, 'head load applicability', 'edit');
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        return $this->render('_map_dcs', [
                'headLoad' => $headLoad,'model'=>$model,'routes'=>$data['routes'],'selectedRoutes'=>$data['selectedRoutes'],
                'selectedOrganization'=>$data['selectedOrganization'],'selectedAllOrg'=>$data['selectedAllOrg'],
            ]);
    }
    
    public function actionGetDcs(){
        
        if(!empty(Yii::$app->request->post('route'))){
            $routeAry = explode(',', Yii::$app->request->post('route'));
            
            
            $finalArray=[];
            if(Yii::$app->request->post('org')==0){
                $dcs = new TblDcs();
                foreach ($routeAry as $route){
                     $dcsAry = $dcs->getRouteDcs($route);
                     $records= ArrayHelper::map($dcsAry, 'dcs_code', 'dcs_name');
                     $finalArray = array_merge($finalArray, $records);
                }
            }else{
                $subCenter = new TblSubCenter();
                foreach ($routeAry as $route){
                     $dcsAry = $subCenter->getRouteSubcenter($route);
                     $records= ArrayHelper::map($dcsAry, 'sub_center_code', 'sub_center_name');
                     $finalArray = array_merge($finalArray, $records);
                }
            }
            
            return \yii\helpers\Json::encode(['data'=>$finalArray,'status'=>'success']);
        }
    }
}
