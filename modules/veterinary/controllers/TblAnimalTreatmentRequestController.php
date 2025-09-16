<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblAnimalTreatmentRequest;
use app\modules\veterinary\models\TblAnimalTreatmentRequestSearch;
use yii\web\NotFoundHttpException;

/**
 * TblAnimalTreatmentRequestController implements the CRUD actions for TblAnimalTreatmentRequest model.
 */
class TblAnimalTreatmentRequestController extends ChildController
{

    /**
     * Lists all TblAnimalTreatmentRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblAnimalTreatmentRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAnimalTreatmentRequest model.
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
     * Finds the TblAnimalTreatmentRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAnimalTreatmentRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblAnimalTreatmentRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
