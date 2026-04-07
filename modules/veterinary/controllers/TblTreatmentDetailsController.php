<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblTreatmentDetails;
use app\modules\veterinary\models\TblTreatmentDetailsSearch;
use yii\web\NotFoundHttpException;

/**
 * TblTreatmentDetailsController implements the CRUD actions for TblTreatmentDetails model.
 */
class TblTreatmentDetailsController extends ChildController
{

    /**
     * Lists all TblTreatmentDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblTreatmentDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTreatmentDetails model.
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
     * Finds the TblTreatmentDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTreatmentDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblTreatmentDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
