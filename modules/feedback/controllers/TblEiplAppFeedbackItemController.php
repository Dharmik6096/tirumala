<?php

namespace app\modules\feedback\controllers;

use Yii;
use app\modules\feedback\models\TblEiplAppFeedbackItem;
use app\modules\feedback\models\TblEiplAppFeedbackItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\feedback\models\TblEiplAppFeedbackItemHistory;

/**
 * TblEiplAppFeedbackItemController implements the CRUD actions for TblEiplAppFeedbackItem model.
 */
class TblEiplAppFeedbackItemController extends \app\controllers\ChildController {

    /**
     * Lists all TblEiplAppFeedbackItem models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEiplAppFeedbackItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblEiplAppFeedbackItem model.
     * @param integer $eipl_app_feedback_item_code
     * @return mixed
     */
    public function actionView($id) {
        $model = TblEiplAppFeedbackItem::find()->joinWith(['createdBy', 'updatedBy'])->where(['tbl_eipl_app_feedback_item.eipl_app_feedback_item_code' => $id])->one();
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new TblEiplAppFeedbackItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblEiplAppFeedbackItem();
        $this->viewFile = 'create';
        if ($this->model->load(\Yii::$app->request->post())) {
            $this->model->created_by = $_SESSION['UserCode'];
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Feedback Item', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblEiplAppFeedbackItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblEiplAppFeedbackItemHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->updated_by = $_SESSION['UserCode'];
//            $this->model->save();
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Eipl App Feedback Item', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['view', 'id' => $this->model->eipl_app_feedback_item_code]);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblEiplAppFeedbackItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->findModel($_POST['id'])->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblEiplAppFeedbackItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $eipl_app_feedback_item_code
     * @return TblEiplAppFeedbackItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppFeedbackItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
