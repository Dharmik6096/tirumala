<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblMRGMeetingPreviousActions;
use app\modules\feedback\models\TblMRGMeetingPreviousActionsSearch;
use yii\web\NotFoundHttpException;

/**
 * TblMrgMeetingPreviousActionsController implements the CRUD actions for TblMRGMeetingPreviousActions model.
 */
class TblMrgMeetingPreviousActionsController extends ChildController
{
    /**
     * Updates an existing TblMRGMeetingPreviousActions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/feedback/tbl-mrg-meeting-master/view', 'id' => $model->MRG_M_Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Finds the TblMRGMeetingPreviousActions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMRGMeetingPreviousActions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMRGMeetingPreviousActions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
