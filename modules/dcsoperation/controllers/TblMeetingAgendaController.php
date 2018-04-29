<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMeetingAgenda;
use app\modules\dcsoperation\models\TblMeetingAgendaSearch;
use app\modules\dcsoperation\models\TblMeetingAttendance;
use app\modules\dcsoperation\models\TblMom;
use app\modules\dcsoperation\models\TblMomAction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMeetingAgendaController implements the CRUD actions for TblMeetingAgenda model.
 */
class TblMeetingAgendaController extends \app\controllers\ChildController
{
   
    /**
     * Lists all TblMeetingAgenda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMeetingAgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMeetingAgenda model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new TblMom();
        $searchModel->meeting_agenda_code = Yii::$app->request->get('id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view', [
            'model' => $this->findModel($id),'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMeetingAgenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblMeetingAgenda();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->meeting_agenda_code]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMeetingAgenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->meeting_agenda_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMeetingAgenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMeetingAgenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMeetingAgenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMeetingAgenda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionViewAttandance($id){
        
        $model = $this->findModel($id);
        
        $searchModel = new TblMeetingAttendance();
        $searchModel->meeting_agenda_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view_attandance', [
                'model' => $model,'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }
    
    
    public function actionMomAction($id){
        
        $model = new TblMom();
        $model = $model->getRecord($id);
        
        $searchModel = new TblMomAction();
        $searchModel->mom_code = Yii::$app->request->get('id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view_mom_action', [
                'model' => $model,'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }
}
