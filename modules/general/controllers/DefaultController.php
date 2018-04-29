<?php

namespace app\modules\general\controllers;

use yii\web\Controller;
use Yii;
use app\modules\general\models\TblBloodgroupSearch;
use app\modules\general\models\TblGenderSearch;
use app\modules\general\models\TblQualificationSearch;
use app\modules\general\models\TblReligionSearch;
use app\modules\general\models\TblRelationshipSearch;

/**
 * Default controller for the `general` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionBackendData(){
        $bloodModel = new TblBloodgroupSearch();
        $bloodModel->is_active = 1;
        $bloodData = $bloodModel->search(Yii::$app->request->queryParams);
        $genderModel = new TblGenderSearch();
        $genderModel->is_active = 1;
        $genderData = $genderModel->search(Yii::$app->request->queryParams);
        $qualificationModel = new TblQualificationSearch();
        $qualificationModel->is_active = 1;
        $qualificationData = $qualificationModel->search(Yii::$app->request->queryParams);
        $religionModel = new TblReligionSearch();
        $religionModel->is_active = 1;
        $religionData = $religionModel->search(Yii::$app->request->queryParams);
        $relationshipModel = new TblRelationshipSearch();
        $relationshipModel->is_active = 1;
        $relationshipData = $relationshipModel->search(Yii::$app->request->queryParams);
        return $this->render('backend_data', [
            'bloodModel' => $bloodModel,
            'bloodData' => $bloodData,
            'genderModel' => $genderModel,
            'genderData' => $genderData,
            'qualificationModel' => $qualificationModel,
            'qualificationData' => $qualificationData,
            'religionModel' => $religionModel,
            'religionData' => $religionData,
            'relationshipModel' => $relationshipModel,
            'relationshipData' => $relationshipData,
        ]);
    }
}
