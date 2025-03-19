<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use app\modules\document\models\TblAttachment;
use app\modules\feedback\models\TblMppSurvey;
use app\modules\feedback\models\TblMppSurveyCompetitorsSearch;
use app\modules\feedback\models\TblMppSurveyGeneralInfo;
use app\modules\feedback\models\TblMppSurveyProbableMembersSearch;
use app\modules\feedback\models\TblMppSurveyProbableSahayakSearch;
use app\modules\feedback\models\TblMppSurveySearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class TblMppSurveyController extends ChildController
{
    public function actionIndex() {
        $searchModel = new TblMppSurveySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $this->model = $this->findModel($id);
        $generalInfoModel = TblMppSurveyGeneralInfo::findOne(['mpp_survey_id' => $id]) ?: new TblMppSurveyGeneralInfo();
        $searchModel = new TblMppSurveyCompetitorsSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->mpp_survey_id = $id;
        $dataProvider = $searchModel->search($params);
        $sahayakSearchModel = new TblMppSurveyProbableSahayakSearch();
        $sahayakSearchModel->mpp_survey_id = $id;
        $sahayakDataProvider = $sahayakSearchModel->search($params);
        $memberSearchModel = new TblMppSurveyProbableMembersSearch();
        $memberSearchModel->mpp_survey_id = $id;
        $memberDataProvider = $memberSearchModel->search($params);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_mpp_survey']),
        ]);
        return $this->render('view', [
            'model' => $this->model,
            'generalInfoModel' => $generalInfoModel,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'sahayakDataProvider' => $sahayakDataProvider,
            'sahayakSearchModel' => $sahayakSearchModel,
            'memberDataProvider' => $memberDataProvider,
            'memberSearchModel' => $memberSearchModel,
            'dataProviderOther' => $dataProviderOther,
            'attachment' => $attachment,
        ]);
    }

    protected function findModel($id) {
        if (($model = TblMppSurvey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
