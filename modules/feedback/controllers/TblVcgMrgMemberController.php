<?php

namespace app\modules\feedback\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\feedback\models\TblVCGMRGMember;
use app\modules\feedback\models\TblVCGMRGMemberHistory;
use app\modules\feedback\models\TblVCGMRGMemberSearch;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use app\modules\document\models\TblAttachment;

/**
 * TblVcgMrgMemberController implements the CRUD actions for TblVCGMRGMember model.
 */
class TblVcgMrgMemberController extends ChildController {

    /**
     * Lists all TblVCGMRGMember models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVCGMRGMemberSearch();
        $dataProvider = $searchModel->searchDcsWise(Yii::$app->request->queryParams);

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
    public function actionView($id) {
        $this->model = TblVCGMRGMember::find()->where(['dcs_code' => $id])->one();
        $searchModel = new TblVCGMRGMemberSearch();
        $searchModel->dcs_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewMember($id) {
        $attachmentModel = new TblAttachment();
        $attachmentModel->module_code = $id;
        $attachments = $attachmentModel->attachmentCode;
        return $this->render('view_member', [
                    'model' => $this->findModel($id),
                    'attachments' => $attachments
        ]);
    }

    /**
     * Updates an existing TblVCGMRGMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVCGMRGMemberHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if (!empty($this->model->end_date)) {
                $this->model->end_date = Yii::$app->formatter->asDate($this->model->end_date, DATE_FORMAT);
                if ($this->model->end_date <= date('Y-m-d')) {
                    $this->model->status = 'INACTIVATE';
                }
            }
            if ($this->model->validate()) {
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

    public function actionApproval() {
        $memberModel = new TblVCGMRGMember();
        $searchModel = new TblVCGMRGMemberSearch();
        $searchModel->status = 'DRAFT';
        $searchModel->pagination = false;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $operation = Yii::$app->request->post('operation');
                $msg = 'VCG/MRG Member is not' . ucfirst(strtolower($operation)) . ' Successfully';
                $type = 'error';
                if ($memberModel->updateStatus($operation, $codes)) {
                    $msg = 'VCG/MRG Member is ' . ucfirst(strtolower($operation)) . ' Successfully';
                    $type = 'success';
                }
                Yii::$app->getSession()->setFlash('success', ['type' => $type,
                    'message' => $msg]);
                return $this->redirect(['approval']);
            }
        }
        return $this->render('approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'memberModel' => $memberModel,
        ]);
    }

    /**
     * Finds the TblVCGMRGMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVCGMRGMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVCGMRGMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
