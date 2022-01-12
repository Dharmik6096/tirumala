<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblCollectionApproval;
use app\modules\collection\models\TblCollectionApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblCollectionApprovalHistory;

/**
 * TblCollectionApprovalController implements the CRUD actions for TblCollectionApproval model.
 */
class TblCollectionApprovalController extends \app\controllers\ChildController {

    /**
     * Lists all TblCollectionApproval models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCollectionApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCollectionApproval model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'type' => 'view',
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblCollectionApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblCollectionApproval();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uuid]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblCollectionApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uuid]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblCollectionApproval model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCollectionApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCollectionApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCollectionApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionApproveCollection($id) {
        $this->model = $this->findModel($id);
        if ($this->model->load(Yii::$app->request->post())) {

            $approve_date = date('Y-m-d H:i:s');
            $this->model->approve_date = $approve_date;
            $days = $this->model->valid_hours / 24;
            $allow_till_date = date('Y-m-d', strtotime('+' . round($days) . ' days'));
            $this->model->allow_till_date = $allow_till_date;
            $this->model->approved_by = !empty(Yii::$app->session->get('UserCode')) ? Yii::$app->session->get('UserCode') : '';
            $this->model->is_approve = 1;
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Collection Approval', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('view', [
                    'type' => 'approve',
                    'model' => $this->model,
        ]);
    }

}
