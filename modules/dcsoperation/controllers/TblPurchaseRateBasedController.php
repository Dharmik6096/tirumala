<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblPurchaseRateBased;
use app\modules\dcsoperation\models\TblPurchaseRateBasedSearch;
use app\modules\dcsoperation\models\TblPurchaseRate;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\components\Model;

/**
 * TblPurchaseRateBasedController implements the CRUD actions for TblPurchaseRateBased model.
 */
class TblPurchaseRateBasedController extends \app\controllers\ChildController
{
   

    /**
     * Lists all TblPurchaseRateBased models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblPurchaseRateBasedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPurchaseRateBased model.
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
     * Creates a new TblPurchaseRateBased model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblPurchaseRateBased();

        if ($model->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblPurchaseRateBased::classname(),[],'manualForm');
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
           if(!$validate){
               
               $modelAttributes = $_POST['TblPurchaseRateBased'];
               foreach ($modelAttributes as $key => $row) {
                   $modelNew = new TblPurchaseRateBased();
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
        $model = new TblPurchaseRateBased();

        if ($model->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblPurchaseRateBased::classname());
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
           if(!$validate){
               
               $modelAttributes = $_POST['TblPurchaseRateBased'];
               $milk_type_code = $modelAttributes[0]['milk_type_code'];
               
               foreach ($modelAttributes as $key => $row) {
                   $modelNew = new TblPurchaseRateBased();
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
                $purchaseModel = new TblPurchaseRate();
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
     * Updates an existing TblPurchaseRateBased model.
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
     * Deletes an existing TblPurchaseRateBased model.
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
     * Finds the TblPurchaseRateBased model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPurchaseRateBased the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblPurchaseRateBased::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
