<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblTankerRateBased;
use app\modules\tankermovement\models\TblTankerRateBasedSearch;
use app\modules\tankermovement\models\TblTankerRate;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\components\Model;

/**
 * TblTankerRateBasedController implements the CRUD actions for TblTankerRateBased model.
 */
class TblTankerRateBasedController extends \app\controllers\ChildController
{
   

    /**
     * 
     * Lists all TblTankerRateBased models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblTankerRateBasedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTankerRateBased model.
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
     * Creates a new TblTankerRateBased model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new TblTankerRateBased();

        if ($model->load(Yii::$app->request->post())) {
            $modelAttributes = Model::createMultiple(TblTankerRateBased::classname(),[],'manualForm');
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
           if(!$validate){
               
               $modelAttributes = $_POST['TblTankerRateBased'];
               foreach ($modelAttributes as $key => $row) {
                   $modelNew = new TblTankerRateBased();
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


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblTankerRateBased model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTankerRateBased the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblTankerRateBased::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
