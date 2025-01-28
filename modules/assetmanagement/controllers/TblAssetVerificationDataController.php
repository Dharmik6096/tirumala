<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetVerificationData;
use app\modules\assetmanagement\models\TblAssetVerificationDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblAssetVerificationDataController implements the CRUD actions for TblAssetVerificationData model.
 */
class TblAssetVerificationDataController extends Controller
{

    /**
     * Lists all TblAssetVerificationData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblAssetVerificationDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblAssetVerificationData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAssetVerificationData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblAssetVerificationData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
