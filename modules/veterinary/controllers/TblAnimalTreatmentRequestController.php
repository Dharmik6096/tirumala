<?php

namespace app\modules\veterinary\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\veterinary\models\TblAnimalTreatmentRequest;
use app\modules\veterinary\models\TblAnimalTreatmentRequestSearch;
use app\modules\veterinary\models\TblDiagnosisDetailsSearch;
use app\modules\veterinary\models\TblTreatmentDetailsSearch;
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
        $searchModel = new TblDiagnosisDetailsSearch();
        $searchModel->animal_treatment_request_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $treatmentSearchModel = new TblTreatmentDetailsSearch();
        $treatmentSearchModel->animal_treatment_request_id = $id;
        $treatmentDataProvider = $treatmentSearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'treatmentSearchModel' => $treatmentSearchModel,
            'treatmentDataProvider' => $treatmentDataProvider,
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
    
    public function actionVetenaryAssistance($id) {
        $controls = [];
        $controls['request_id'] = $id;
        $controls['p_report_name'] = 'Vetenary Assistance';
        $this->printDocument($controls, 'staff/VetenaryAssistance', 'VetenaryAssistance', 'pdf');
    }
}
