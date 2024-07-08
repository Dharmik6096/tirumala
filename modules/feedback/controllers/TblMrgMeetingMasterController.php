<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use app\modules\feedback\models\TblMRGMeetingAttandanceSearch;
use app\modules\feedback\models\TblMRGMeetingFeedbackSearch;
use app\modules\feedback\models\TblMRGMeetingInfoSharingSearch;
use Yii;
use app\modules\feedback\models\TblMRGMeetingMaster;
use app\modules\feedback\models\TblMRGMeetingMasterSearch;
use app\modules\feedback\models\TblMRGMeetingMOMSearch;
use app\modules\feedback\models\TblMRGMeetingOrgMapping;
use app\modules\feedback\models\TblMRGMeetingOrgMappingSearch;
use app\modules\feedback\models\TblMRGMeetingPreviousActionsSearch;
use app\modules\feedback\models\TblMRGMeetingStatisticsSearch;
use yii\web\NotFoundHttpException;

/**
 * TblMrgMeetingMasterController implements the CRUD actions for TblMRGMeetingMaster model.
 */
class TblMrgMeetingMasterController extends ChildController
{
    /**
     * Lists all TblMRGMeetingMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMRGMeetingMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMRGMeetingMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $searchModel = new TblMRGMeetingAttandanceSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->MRG_M_Id = $id;
        $dataProvider = $searchModel->search($params);
        $statisticsSearchModel = new TblMRGMeetingStatisticsSearch();
        $statisticsSearchModel->MRG_M_Id = $id;
        $statisticsDataProvider = $statisticsSearchModel->search($params);
        $momSearchModel = new TblMRGMeetingMOMSearch();
        $momSearchModel->MRG_M_Id = $id;
        $momDataProvider = $momSearchModel->search($params);
        $feedbackSearchModel = new TblMRGMeetingFeedbackSearch();
        $feedbackSearchModel->MRG_M_Id = $id;
        $feedbackDataProvider = $feedbackSearchModel->search($params);
        $sharingSearchModel = new TblMRGMeetingInfoSharingSearch();
        $sharingSearchModel->MRG_M_Id = $id;
        $sharingDataProvider = $sharingSearchModel->search($params);
        $previousSearchModel = new TblMRGMeetingPreviousActionsSearch();
        $previousSearchModel->MRG_M_Id = $id;
        $previousDataProvider = $previousSearchModel->search($params);
        $mappingSearchModel = new TblMRGMeetingOrgMappingSearch();
        $mappingSearchModel->MRG_M_Id = $id;
        $mappingDataProvider = $mappingSearchModel->search($params);
        return $this->render('view', [
            'model' => $this->model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'statisticsDataProvider' => $statisticsDataProvider,
            'statisticsSearchModel' => $statisticsSearchModel,
            'momDataProvider' => $momDataProvider,
            'momSearchModel' => $momSearchModel,
            'feedbackDataProvider' => $feedbackDataProvider,
            'feedbackSearchModel' => $feedbackSearchModel,
            'sharingDataProvider' => $sharingDataProvider,
            'sharingSearchModel' => $sharingSearchModel,
            'previousDataProvider' => $previousDataProvider,
            'previousSearchModel' => $previousSearchModel,
            'mappingDataProvider' => $mappingDataProvider,
            'mappingSearchModel' => $mappingSearchModel,
        ]);
    }

    /**
     * Creates a new TblMRGMeetingMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblMRGMeetingMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->MRG_M_Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMRGMeetingMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->MRG_M_Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMRGMeetingMaster model.
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
     * Finds the TblMRGMeetingMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMRGMeetingMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMRGMeetingMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
