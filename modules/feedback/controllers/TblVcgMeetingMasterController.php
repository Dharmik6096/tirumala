<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use app\modules\feedback\models\TblVCGMeetingAttandanceSearch;
use app\modules\feedback\models\TblVCGMeetingFeedbackSearch;
use app\modules\feedback\models\TblVCGMeetingInfoSharingSearch;
use Yii;
use app\modules\feedback\models\TblVCGMeetingMaster;
use app\modules\feedback\models\TblVCGMeetingMasterSearch;
use app\modules\feedback\models\TblVCGMeetingMOMSearch;
use app\modules\feedback\models\TblVCGMeetingNonPouringMembersSearch;
use app\modules\feedback\models\TblVCGMeetingPreviousActionsSearch;
use app\modules\feedback\models\TblVCGMeetingStatisticsSearch;
use app\modules\feedback\models\TblVCGMRGUpdateSearch;
use yii\web\NotFoundHttpException;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;

/**
 * TblVcgMeetingMasterController implements the CRUD actions for TblVCGMeetingMaster model.
 */
class TblVcgMeetingMasterController extends ChildController
{
    /**
     * Lists all TblVCGMeetingMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVCGMeetingMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVCGMeetingMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $searchModel = new TblVCGMeetingAttandanceSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->VCG_M_Id = $id;
        $dataProvider = $searchModel->search($params);
        $statisticsSearchModel = new TblVCGMeetingStatisticsSearch();
        $statisticsSearchModel->VCG_M_Id = $id;
        $statisticsDataProvider = $statisticsSearchModel->search($params);
        $memberSearchModel = new TblVCGMeetingNonPouringMembersSearch();
        $memberSearchModel->VCG_M_Id = $id;
        $memberDataProvider = $memberSearchModel->search($params);
        $momSearchModel = new TblVCGMeetingMOMSearch();
        $momSearchModel->VCG_M_Id = $id;
        $momDataProvider = $momSearchModel->search($params);
        $feedbackSearchModel = new TblVCGMeetingFeedbackSearch();
        $feedbackSearchModel->VCG_M_Id = $id;
        $feedbackDataProvider = $feedbackSearchModel->search($params);
        $sharingSearchModel = new TblVCGMeetingInfoSharingSearch();
        $sharingSearchModel->VCG_M_Id = $id;
        $sharingDataProvider = $sharingSearchModel->search($params);
        $previousSearchModel = new TblVCGMeetingPreviousActionsSearch();
        $previousSearchModel->VCG_M_Id = $id;
        $previousDataProvider = $previousSearchModel->search($params);
        $updateSearchModel = new TblVCGMRGUpdateSearch();
        $updateSearchModel->VCG_M_id = $id;
        $updateDataProvider = $updateSearchModel->search($params);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => $id, 'module_name' => 'tbl_VCG_meeting_master']),
        ]);
        return $this->render('view', [
            'model' => $this->model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'statisticsDataProvider' => $statisticsDataProvider,
            'statisticsSearchModel' => $statisticsSearchModel,
            'memberDataProvider' => $memberDataProvider,
            'memberSearchModel' => $memberSearchModel,
            'momDataProvider' => $momDataProvider,
            'momSearchModel' => $momSearchModel,
            'feedbackDataProvider' => $feedbackDataProvider,
            'feedbackSearchModel' => $feedbackSearchModel,
            'sharingDataProvider' => $sharingDataProvider,
            'sharingSearchModel' => $sharingSearchModel,
            'previousDataProvider' => $previousDataProvider,
            'previousSearchModel' => $previousSearchModel,
            'updateDataProvider' => $updateDataProvider,
            'updateSearchModel' => $updateSearchModel,
            'dataProviderOther' => $dataProviderOther,
            'attachment' => $attachment,
        ]);
    }

    /**
     * Creates a new TblVCGMeetingMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblVCGMeetingMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->VCG_M_Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblVCGMeetingMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->VCG_M_Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblVCGMeetingMaster model.
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
     * Finds the TblVCGMeetingMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMeetingMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVCGMeetingMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
