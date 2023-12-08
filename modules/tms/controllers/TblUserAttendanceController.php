<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblUserAttendance;
use app\modules\tms\models\TblUserAttendanceSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;

/**
 * TblUserAttendanceController implements the CRUD actions for TblUserAttendance model.
 */
class TblUserAttendanceController extends ChildController
{
    /**
     * Lists all TblUserAttendance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblUserAttendanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUserAttendance model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblUserAttendanceSearch();
        $user_attachment = new TblAttachment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $attachmentDataProvider = new ActiveDataProvider([
            'query' => $user_attachment->find()->where(['module_code' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
                    'user_attachment' => $user_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
        ]);
    }

    /**
     * Finds the TblUserAttendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUserAttendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblUserAttendance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
