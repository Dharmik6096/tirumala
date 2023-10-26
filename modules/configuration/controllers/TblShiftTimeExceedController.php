<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblShiftTimeExceed;
use app\modules\configuration\models\TblShiftTimeExceedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\configuration\models\TblShiftTimeExceedHistory;
use app\modules\general\models\TblProcessApprovalHistory;

/**
 * TblShiftTimeExceedController implements the CRUD actions for TblShiftTimeExceed model.
 */
class TblShiftTimeExceedController extends \app\controllers\ChildController
{

    /**
     * Lists all TblShiftTimeExceed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblShiftTimeExceedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pending_approval' => FALSE
        ]);
    }

    /**
     * Displays a single TblShiftTimeExceed model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblShiftTimeExceed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblShiftTimeExceed();
        $this->viewFile = 'create';
        $dateWithShift = Yii::$app->general->getCurrentDateShift($this->model);
        $this->model->shift_code = $dateWithShift[0];
        $this->model->date_time_of_collection = $dateWithShift[2];
        $this->model->scenario = 'create_shift_time';
        $save_model = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->shift_time_exceed_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->date_time_of_collection = date('d-m-Y');
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($this->model->org_type == 'BMC') {
                $this->model->org_code = $this->model->bmc_code;
            } else if ($this->model->org_type == 'MCC') {
                $this->model->org_code = $this->model->mcc_plant_code;
            } else {
                $this->model->org_code = $this->model->dcs_code;
            }
            if ($this->model->validate()) {
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setApprovalData($this->model->union_code, 'tbl_shift_time_exceed', $this->model->shift_time_exceed_code, $save_model, $approval_stages);
                $this->model->status = empty($approval_stages) ? 'Approve' : 'Register';
                $save_model[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($save_model, ['Shift Time Exceed', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblShiftTimeExceed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_time_exceed_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblShiftTimeExceed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblShiftTimeExceed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblShiftTimeExceed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblShiftTimeExceed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetStandardTime()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TblShiftTimeExceed();
        $data = $model->getStandardTimeData(Yii::$app->request->post());
        return ['standard_time' => $data,];
    }

    public function actionPendingApproval()
    {
        $searchModel = new TblShiftTimeExceedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, TRUE);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pending_approval' => TRUE
        ]);
    }

    public function actionApproveShiftTimeExceed($id)
    {
        $model = TblProcessApproval::findOne($id);
        $model->scenario = 'approve';
        $model_save = [];
        $approvalHistoryModel = new TblProcessApprovalHistory();
        Yii::$app->operation->history($model, $approvalHistoryModel, 'UPDATE');
        $model_save[] = $approvalHistoryModel;
        $shiftTimeExceedModel = $this->findModel($model->process_code);
        $shiftTimeExceedModel->scenario = 'approval_shift_time';
        $exceedTime = Yii::$app->general->getCurrentDateShift($shiftTimeExceedModel);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save[] = $model;
            if (!empty($model_save)) {
                $next_count = TblProcessApproval::find()
                    ->where(['process_code' => $model->process_code, 'status' => 0])
                    ->andWhere(['<>', 'process_approval_code', $model->process_approval_code])
                    ->count();
                if ($model->status == '2') {
                    $status = 'Reject';
                } else if ($model->status == '1' && $next_count > 0) {
                    $status = 'Inprogress';
                } else {
                    $status = 'Approve';
                }
                $exceed_time = !empty(Yii::$app->request->post()['TblShiftTimeExceed']['exceed_time']) ? Yii::$app->request->post()['TblShiftTimeExceed']['exceed_time'] : $shiftTimeExceedModel->exceed_time;
                $model->remarks = $model->remarks . '_' . $shiftTimeExceedModel->exceed_time . '_' . $exceed_time;
                $historyModel = new TblShiftTimeExceedHistory();
                Yii::$app->operation->history($shiftTimeExceedModel, $historyModel, UPDATE);
                $model_save[] = $historyModel;
                $shiftTimeExceedModel->status = $status;
                $shiftTimeExceedModel->status_datetime = date('Y-m-d H:i:s');
                $shiftTimeExceedModel->status_by = Yii::$app->session['UserCode'];
                $shiftTimeExceedModel->status_remarks = $model->remarks;
                $shiftTimeExceedModel->exceed_time = $exceed_time;
                $model_save[] = $shiftTimeExceedModel;
                $model_save[] = $model;
                $transaction = $this->generalModel->saveTransaction($model_save, ['Shift Time Exceed Approval', 'edit']);

                if ($transaction == 'customRedirect') {
                    return $this->redirect(['pending-approval']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'message' => 'Member provisional already approved by other user.'
                ]);
            }
        }
        return $this->render('approve_shift_time_exceed', [
            'model' => $model,
            'shiftTimeExceedModel' => $shiftTimeExceedModel,
            'exceedTime' => $exceedTime[1],
        ]);
    }
}
