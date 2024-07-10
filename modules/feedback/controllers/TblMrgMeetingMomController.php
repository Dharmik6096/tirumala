<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblMRGMeetingMOM;
use app\modules\feedback\models\TblMRGMeetingMOMHistory;
use yii\web\NotFoundHttpException;

/**
 * TblMrgMeetingMomController implements the CRUD actions for TblMRGMeetingMOM model.
 */
class TblMrgMeetingMomController extends ChildController
{
    /**
     * Updates an existing TblMRGMeetingMOM model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblMRGMeetingMOMHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['MRG Meeting Mom', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['/feedback/tbl-mrg-meeting-master/view', 'id' => $this->model->MRG_M_Id]);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblMRGMeetingMOM model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMRGMeetingMOM the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMRGMeetingMOM::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
