<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblBulkNotification;
use app\modules\sms\models\TblBulkNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblBulkNotificationHistory;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\sms\models\TblBulkNotificationApplicability;
use app\modules\sms\models\TblBulkNotificationApplicabilityHistory;

/**
 * TblBulkNotificationController implements the CRUD actions for TblBulkNotification model.
 */
class TblBulkNotificationController extends \app\controllers\ChildController {

    /**
     * Lists all TblBulkNotification models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBulkNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBulkNotification model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBulkNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBulkNotification();
        $this->viewFile = 'create';
        $this->model->app_type = 1;
        $this->model->login_type = 'MEMBER';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->receiver_type = 'APP_NOTIFICATION';
            $this->model->content_id = Yii::$app->general->getforeignkey($this->model->apiMaster, 'api_master_id');
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->entry_datetime = date('Y-m-d H:i:s');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Bulk Notification', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblBulkNotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblBulkNotificationHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->entry_datetime = !empty($this->model->entry_datetime) ? date('Y-m-d', strtotime($this->model->entry_datetime)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Customer Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBulkNotification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblBulkNotificationHistory();
        Yii::$app->operation->history($this->model, $historyModel, 'DELETE');
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBulkNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBulkNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBulkNotification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulkNotificationApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBulkNotificationApplicability();
        $value = [];
        if (in_array(strtolower($model->login_type), ['farmer', 'vsp', 'area_manager', 'route_supervisor'])) {
            $value['DCS'] = 'VLCC';
        } elseif (in_array(strtolower($model->login_type), ['mcc_incharge'])) {
            $value['MCC'] = 'MCC';
        } elseif (in_array(strtolower($model->login_type), ['maintenance_staff', 'procurement_staff'])) {
            $value['USER'] = 'USER';
        } else {
            $value['USER'] = 'USER';
        }
        $appModel->model->wef_date = $model->wef_date;
        $appModel->model->company_code = $model->company_code;
        $appModel->is_union = false;
        $appModel->union_code = $model->company_code;
        $appModel->field_name = 'bulk_notification_id';
        $appModel->field_value = $id;
        $appModel->trans_label = 'Bulk Notification Applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['dcs_mcc_user'];
        $appModel->header_title = ' (' . $model->login_type . ':' . $model->message . ')';
        $appModel->dcs_filters = $value;
        $appModel->login_type = $model->login_type;

        $appModel->fields = [
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }, 'filter' => FALSE],
            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'applicable_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'Applicable Name'), 'value' => function($model) {
                    if (strtolower($model->applicable_for) == 'dcs') {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'villagename');
                    } else if (strtolower($model->applicable_for) == 'mcc') {
                        return Yii::$app->general->getforeignkey($model->mccCode, 'mccname');
                    } else {
                        return Yii::$app->general->getforeignkey($model->userCode, 'name');
                    }
                }],
            'status' => ['view' => ['grid'], 'value' => 'status', 'value' => function($model) {
                    return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
                }, 'filter' => FALSE],
        ];
        $appModel->actions = [
            'delete' => ['option' => 'applicable_code,bulk_notification_app_code,tbl-bulk-notification/delete-mapping'],
        ];


        return $appModel->createApp();
    }

    public function actionDeleteMapping() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblBulkNotificationApplicabilityHistory();
            $record = TblBulkNotificationApplicability::find()->where(['bulk_notification_app_code' => Yii::$app->request->post('id')])->one();
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
