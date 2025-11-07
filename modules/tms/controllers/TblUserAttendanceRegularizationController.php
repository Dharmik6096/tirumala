<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblUserAttendanceRegularization;
use app\modules\tms\models\TblUserAttendanceRegularizationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\ChildController;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\tms\models\TblUserAttendanceRegularizationHistory;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\tms\models\TblUserAttendanceHistory;

/**
 * TblUserAttendanceRegularizationController implements the CRUD actions for TblUserAttendanceRegularization model.
 */
class TblUserAttendanceRegularizationController extends ChildController {

    /**
     * Lists all TblUserAttendanceRegularization models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblUserAttendanceRegularizationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRejectRegularization($id) {
        $model = TblProcessApproval::findOne($id);
        $regularizationModel = TblUserAttendanceRegularization::findOne($model->process_code);
        $regularizationModel->scenario = 'attendanceRegularization';
        $actualInOuttimeData = $regularizationModel->userAttendance;
        if (!empty($actualInOuttimeData)) {
            $regularizationModel->actual_in_time = Yii::$app->controls->view_time($actualInOuttimeData->in_time);
            $regularizationModel->actual_out_time = Yii::$app->controls->view_time($actualInOuttimeData->out_time);
            $regularizationModel->process_approval_code = $id;
        }

        if (Yii::$app->request->post()) {
            $model->scenario = 'approve';
            $historyApproval = new TblProcessApprovalHistory();
            Yii::$app->operation->history($model, $historyApproval, UPDATE);
            $model_save = [];
            $model_save[] = $historyApproval;
            $model_save[] = $model;

            if (!empty($model_save)) {
                $model->status = '2';
                $model->ApprovalList($model, $model_save, $status);
                $historyModel = new TblUserAttendanceRegularizationHistory();
                Yii::$app->operation->history($regularizationModel, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $regularizationModel->status = '3';
                $regularizationModel->rejection_remark = Yii::$app->request->post('TblUserAttendanceRegularization')['rejection_remark'];

                if ($regularizationModel->validate()) {
                    $model_save[] = $regularizationModel;
                    $transaction = $this->generalModel->saveTransaction($model_save, ['Regularization Reject', 'edit']);
                    if ($transaction === 'customRedirect') {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => 'Regularization has been successfully rejected.']);
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Regularization could not be rejected.']);
                    }
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode(ActiveForm::validate($regularizationModel));
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode(['status' => 'error', 'msg' => 'Regularization attendance already rejected by another user.']);
        }
        return $this->renderAjax('_search', ['regularizationModelData' => $regularizationModel]);
    }

    public function actionApproveRegularization($id) {
        $model = TblProcessApproval::findOne($id);
        $model->scenario = 'approve';
        $historyApproval = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $historyApproval, UPDATE);
        $model_save = [];
        $model_save[] = $historyApproval;
        $model_save[] = $model;
        if (!empty($model_save)) {
            $model->status = '1';
            $model->ApprovalList($model, $model_save, $status);
            $userAttendanceRegularizationModel = $this->findModel($model->process_code);
            $historyModel = new TblUserAttendanceRegularizationHistory();
            Yii::$app->operation->history($userAttendanceRegularizationModel, $historyModel, UPDATE);
            $model_save[] = $historyModel;
            $userAttendanceRegularizationModel->status = $status == 'Inprogress' ? 1 : 2;
            $model_save[] = $userAttendanceRegularizationModel;

            if($userAttendanceRegularizationModel->status == 2){
                $userAttendanceModel = $userAttendanceRegularizationModel->userAttendance;
                if (!empty($userAttendanceModel)) {
                    $userAttendanceHistory = new TblUserAttendanceHistory();
                    Yii::$app->operation->history($userAttendanceModel, $userAttendanceHistory, UPDATE);
                    $model_save[] = $userAttendanceHistory;

                    $userAttendanceModel->in_time = $userAttendanceRegularizationModel->requested_in_time;
                    $userAttendanceModel->out_time = $userAttendanceRegularizationModel->requested_out_time;
                    $userAttendanceModel->duration = (new \DateTime($userAttendanceModel->in_time))->diff(new \DateTime($userAttendanceModel->out_time))->format('%H:%I:%S');
                    $model_save[] = $userAttendanceModel;
                }
            }

           $transaction = $this->generalModel->saveTransaction($model_save, ['Regularization Approval', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'Regularization has been successfully approved.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'Regularization approval failed.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'Regularization attendance already approved by another user.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblUserAttendanceRegularization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUserAttendanceRegularization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblUserAttendanceRegularization::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
