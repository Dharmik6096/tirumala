<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblVCGMeetingMOM;
use app\modules\feedback\models\TblVCGMeetingMOMSearch;
use yii\web\NotFoundHttpException;

/**
 * TblVcgMeetingMomController implements the CRUD actions for TblVCGMeetingMOM model.
 */
class TblVcgMeetingMomController extends ChildController
{
    /**
     * Updates an existing TblVCGMeetingMOM model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/feedback/tbl-vcg-meeting-master/view', 'id' => $model->VCG_M_Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Finds the TblVCGMeetingMOM model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMeetingMOM the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVCGMeetingMOM::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
