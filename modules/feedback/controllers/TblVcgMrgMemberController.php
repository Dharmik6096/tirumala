<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblVCGMRGMember;
use app\modules\feedback\models\TblVCGMRGMemberHistory;
use app\modules\feedback\models\TblVCGMRGMemberSearch;
use yii\web\NotFoundHttpException;

/**
 * TblVcgMrgMemberController implements the CRUD actions for TblVCGMRGMember model.
 */
class TblVcgMrgMemberController extends ChildController
{

    /**
     * Lists all TblVCGMRGMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVCGMRGMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVCGMRGMember model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVCGMRGMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblVCGMRGMember();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->VCG_MRG_member_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblVCGMRGMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVCGMRGMemberHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if(!empty($this->model->end_date)){
                $this->model->end_date = Yii::$app->formatter->asDate($this->model->end_date, DATE_FORMAT);
                if($this->model->end_date <= date('Y-m-d')){
                    $this->model->status = 'INACTIVATE';
                }
            }
            if($this->model->validate()){
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['VCG MRG Member', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update', [
            'model' => $this->model
        ]);
    }

    /**
     * Deletes an existing TblVCGMRGMember model.
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
     * Finds the TblVCGMRGMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMRGMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVCGMRGMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
