<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblVCGMeetingFeedback;
use app\modules\feedback\models\TblVCGMeetingFeedbackHistory;
use yii\web\NotFoundHttpException;

/**
 * TblVcgMeetingFeedbackController implements the CRUD actions for TblVCGMeetingFeedback model.
 */
class TblVcgMeetingFeedbackController extends ChildController
{
    /**
     * Updates an existing TblVCGMeetingFeedback model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVCGMeetingFeedbackHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['VCG Meeting Feedback', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['/feedback/tbl-vcg-meeting-master/view', 'id' => $this->model->VCG_M_Id]);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblVCGMeetingFeedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMeetingFeedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVCGMeetingFeedback::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
