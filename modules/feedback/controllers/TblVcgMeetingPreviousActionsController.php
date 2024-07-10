<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblVCGMeetingPreviousActions;
use app\modules\feedback\models\TblVCGMeetingPreviousActionsHistory;
use yii\web\NotFoundHttpException;

/**
 * TblVcgMeetingPreviousActionsController implements the CRUD actions for TblVCGMeetingPreviousActions model.
 */
class TblVcgMeetingPreviousActionsController extends ChildController
{
    /**
     * Updates an existing TblVCGMeetingPreviousActions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVCGMeetingPreviousActionsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['VCG Meeting Previous Action', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['/feedback/tbl-vcg-meeting-master/view', 'id' => $this->model->VCG_M_Id]);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblVCGMeetingPreviousActions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMeetingPreviousActions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVCGMeetingPreviousActions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
