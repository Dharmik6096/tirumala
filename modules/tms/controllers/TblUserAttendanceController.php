<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblUserAttendance;
use app\modules\tms\models\TblUserAttendanceSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblUserAttendanceDetail;

/**
 * TblUserAttendanceController implements the CRUD actions for TblUserAttendance model.
 */
class TblUserAttendanceController extends ChildController {

    /**
     * Lists all TblUserAttendance models.
     * @return mixed
     */
    public function actionIndex() {
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
        $model = $this->findModel($id);
        $user_detail = new TblUserAttendanceDetail();
        $userDetailDataProvider = new ActiveDataProvider([
            'query' => $user_detail->find()->where(['user_code' => $model->user_code, 'attendance_date' => $model->attendance_date]),
        ]);

        $UserDetailIds = array_map(function($e) {
            return $e->attendance_detail_code;
        }, $userDetailDataProvider->getModels());

        $user_attachment = new TblAttachment();
        $attachmentDataProvider = new ActiveDataProvider([
            'query' => $user_attachment->find()->where(['module_code' => $UserDetailIds, 'module_name' => ['tbl_user_attendance_detail_in', 'tbl_user_attendance_detail_out']]),
        ]);

        return $this->render('view', [
                    'model' => $model,
                    'user_attachment' => $user_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
                    'user_detail' => $user_detail,
                    'userDetailDataProvider' => $userDetailDataProvider
        ]);
    }

    /**
     * Finds the TblUserAttendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUserAttendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblUserAttendance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
