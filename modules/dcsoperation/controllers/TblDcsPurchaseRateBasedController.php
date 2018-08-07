<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBased;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBasedSearch;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\components\Model;

/**
 * TblDcsPurchaseRateBasedController implements the CRUD actions for TblDcsPurchaseRateBased model.
 */
class TblDcsPurchaseRateBasedController extends \app\controllers\ChildController
{
   

    /**
     * Lists all TblDcsPurchaseRateBased models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblDcsPurchaseRateBasedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsPurchaseRateBased model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDcsPurchaseRateBased model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblDcsPurchaseRateBased();

        if ($model->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblDcsPurchaseRateBased::classname(),[],'manualForm');
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
           if(!$validate){
               
               $modelAttributes = $_POST['TblDcsPurchaseRateBased'];
               foreach ($modelAttributes as $key => $row) {
                   $modelNew = new TblDcsPurchaseRateBased();
                   $modelNew->attributes = $row;
                   $modelNew->is_active=1;
                   Yii::$app->operation->defaults($modelNew, INSERT);
                   $modelNew->save();
               }
               
                $result = ['status'=>'success'];
                    Yii::$app->response->format = trim(Response::FORMAT_JSON);
                    return $result;
            }else{
                $error = ActiveForm::validate($model);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $validate;
            }
        }
    }

    public function actionCreateAuto()
    {
        $model = new TblDcsPurchaseRateBased();

        if ($model->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblDcsPurchaseRateBased::classname());
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
           if(!$validate){
               
               $modelAttributes = $_POST['TblDcsPurchaseRateBased'];
               $milk_type_code = $modelAttributes[0]['milk_type_code'];
               
               foreach ($modelAttributes as $key => $row) {
                   $modelNew = new TblDcsPurchaseRateBased();
                   $modelNew->attributes = $row;
                   $modelNew->milk_type_code = $milk_type_code;
                   $modelNew->is_active=1;
                   Yii::$app->operation->defaults($modelNew, INSERT);
                   $modelNew->save();
                   Yii::$app->session->set('start'.($key+1),$modelNew->start_range);
                   Yii::$app->session->set('end'.($key+1),$modelNew->end_range);
                   Yii::$app->session->set('rate'.($key+1),$modelNew->kg_rate);
               }
               Yii::$app->session->set('milk_type_code',$modelNew->milk_type_code);
               Yii::$app->session->set('formula',0);
               if(!empty($modelNew->formula)){
                $purchaseModel = new TblDcsPurchaseRate();
                $purchaseModel = $purchaseModel->getRecord($modelNew->purchase_rate_code);
                $purchaseModel->formula=$modelNew->formula;
                $purchaseModel->save(false);
                Yii::$app->session->set('formula',$modelNew->formula);
               }
               
                $result = ['status'=>'success','id'=>$modelNew->purchase_rate_code];
                    Yii::$app->response->format = trim(Response::FORMAT_JSON);
                    return $result;
            }else{
                $error = ActiveForm::validate($model);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return $validate;
            }
        }
    }
    /**
     * Updates an existing TblDcsPurchaseRateBased model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rate_detail_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDcsPurchaseRateBased model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDcsPurchaseRateBased model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsPurchaseRateBased the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblDcsPurchaseRateBased::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
