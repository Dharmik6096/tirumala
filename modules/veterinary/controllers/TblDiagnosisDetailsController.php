<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use app\modules\document\models\TblAttachment;
use Yii;
use app\modules\veterinary\models\TblDiagnosisDetails;
use app\modules\veterinary\models\TblDiagnosisDetailsSearch;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * TblDiagnosisDetailsController implements the CRUD actions for TblDiagnosisDetails model.
 */
class TblDiagnosisDetailsController extends ChildController
{

    /**
     * Lists all TblDiagnosisDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblDiagnosisDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDiagnosisDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $attachment = new TblAttachment();
        $dataProvider = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => (string)$id, 'module_name' => 'tbl_diagnosis_details']),
        ]);
        return $this->render('view', [
            'model' => $model,
            'attachment' => $attachment,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDiagnosisDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDiagnosisDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblDiagnosisDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
