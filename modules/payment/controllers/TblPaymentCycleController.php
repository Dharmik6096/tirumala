<?php

namespace app\modules\payment\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\payment\models\TblPaymentCycleSearch;
use app\modules\payment\models\TblPaymentCycleHistory;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycleApplicabilitySearch;
use DateTime;
use app\controllers\ChildController;
use ReflectionClass;
use yii\web\Response;
use yii\helpers\Json;
use webvimark\modules\UserManagement\components\GhostHtml;
use app\modules\payment\models\TblPaymentCycleApplicabilityHistory;
use yii\helpers\Url;
use webvimark\modules\UserManagement\models\User;

/**
 * TblPaymentCycleController implements the CRUD actions for TblPaymentCycle model.
 */
class TblPaymentCycleController extends ChildController {

    public $freeAccessActions = ['payment-cycle-list'];

    /**
     * Lists all TblPaymentCycle models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPaymentCycleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPaymentCycle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblPaymentCycle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblPaymentCycle();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->is_active = 1;
            $shift_from_time = '06:00:00';
            $shift_to_time = '06:00:00';
            $shift_from_type = 1;
            $shift_to_type = 1;
            if ($this->model->from_shift == 2) {
                $shift_from_time = '18:00:00';
                $shift_from_type = 2;
            }
            if ($this->model->to_shift == 2) {
                $shift_to_time = '18:00:00';
                $shift_to_type = 2;
            }

            $from_date = new DateTime($this->model->from_date . ' ' . $shift_from_time);
            $to_date = new DateTime($this->model->to_date . ' ' . $shift_to_time);
            $this->model->from_date = $from_date->format('Y-m-d') . ' ' . $shift_from_time;
            $this->model->to_date = $to_date->format('Y-m-d') . ' ' . $shift_to_time;

            if ($this->model->validate()) {
                $save_model = [];
                for ($i = 0; $from_date < $to_date; $i++) {
                    $shift_from_date = $from_date->format('Y-m-d');
                    $new_model = new ReflectionClass($this->model->className());
                    $model = $new_model->newInstanceArgs();
                    $data = $this->model->attributes;
                    $model->setAttributes($data);

                    $tmp_Date = clone $from_date;
                    $end_date = clone $from_date;
                    $tmp_Date->modify("last day of this month");
                    $end_date->modify("+" . ($this->model->interval_value - 1) . " day");
                    $compare_date = clone $end_date;
                    $compare_date->modify("+1 day");
                    if ($this->model->check_month && ($compare_date) >= $tmp_Date) {
                        $end_date = clone $tmp_Date;
                    }
                    if ($end_date > $to_date) {
                        $end_date = clone $to_date;
                    }
                    $from_date = clone $end_date;
                    $from_date->modify("+ 1 day");
                    if ($i == 0) {
                        $from_time = $shift_from_time;
                        $to_time = '18:00:00';
                        $from_shift = $shift_from_type;
                        $to_shift = 2;
                    } else if ($from_date >= $to_date) {
                        $from_time = '06:00:00';
                        $to_time = $shift_to_time;
                        $from_shift = 1;
                        $to_shift = $shift_to_type;
                    } else {
                        $from_shift = 1;
                        $to_shift = 2;
                        $from_time = '06:00:00';
                        $to_time = '18:00:00';
                    }
                    $model->from_date = $shift_from_date . ' ' . $from_time;
                    $model->to_date = $end_date->format('Y-m-d') . ' ' . $to_time;
                    $model->from_shift = $from_shift;
                    $model->to_shift = $to_shift;
                    $interval = date_diff(new DateTime($model->from_date), new DateTime($model->to_date));
                    $model->interval_value = $interval->days + 1;
                    $save_model[] = $model;
                }
                $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Cycle', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblPaymentCycle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblPaymentCycleHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Payment Cycle', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblPaymentCycle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_payment_cycle', Yii::$app->request->post('id'), 'payment_cycle_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            if($this->model->disableDelete()){
                $historyModel = new TblPaymentCycleHistory();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
            } else {
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblPaymentCycle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPaymentCycle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPaymentCycle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPaymentCycleApplicability($id) {
        $cmodel = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblPaymentCycleApplicability();
        $appModel->model->payment_cycle_code = $id;
        $appModel->searchModel = new TblPaymentCycleApplicabilitySearch();
        $appModel->field_name = 'payment_cycle_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'payment cycle applicability';
        $appModel->top_section = FALSE;
        $appModel->is_union = FALSE;
        $appModel->union_code = $cmodel->union_code;
        $appModel->bmc_field_name = 'applicable_code';

        $appModel->options = ['bmc'];
        $appModel->payment = true;
        $appModel->load_data_on_apply_to_checkbox = true;
        $appModel->customer_type_wise_entry = true;
        $appModel->assignMultiData = true;
        $appModel->setModelFields = true;
        $appModel->assignMultiDataKey = 'applicable_type';
        $appModel->assignDataKey = 'applicable_code';
        $appModel->assignStaticData = [
            'applicable_for' => 'BMC',
            'from_date' => $cmodel->from_date,
            'to_date' => $cmodel->to_date
        ];
        $fromShift = Yii::$app->general->getforeignkey($cmodel->fromShift, 'shift');
        $fromShift = !empty($fromShift) && $fromShift != 'N/A' ? substr($fromShift, 0, 1) : '';
        $toShift = Yii::$app->general->getforeignkey($cmodel->toShift, 'shift');
        $toShift = !empty($toShift) && $toShift != 'N/A' ? substr($toShift, 0, 1) : '';
        $appModel->header_title = '[' . Yii::$app->controls->view_date($cmodel->from_date) . '(' . $fromShift . ')' . ' to ' . Yii::$app->controls->view_date($cmodel->to_date) . '(' . $toShift . ')' . ']';
        $appModel->title = Yii::$app->controls->view_date($cmodel->from_date) . ' to ' . Yii::$app->controls->view_date($cmodel->to_date);
        $appModel->fields = ['from_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date);
                }],
            'to_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->to_date);
                }],
            'applicable_type' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }],
            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
            'applicable_name' => ['view' => ['grid'], 'value' => function($model) {
                    return $model->getName($model->applicable_for);
                }],
        ];
        $appModel->fields['data_lock_bmca'] = ['view' => ['grid'], 'value' => function($model) {
                $class = $model->data_lock_bmc == 1 ? 'fa-lock' : 'fa-unlock';
                $title = $model->data_lock_bmc == 1 ? 'Data Unlock - BMC' : 'Data Lock - BMC';
                $url = $model->data_lock_bmc == 1 ? '/payment/tbl-payment-cycle/bmc-data-unlock' : '/payment/tbl-payment-cycle/bmc-data-lock';
                $popupClass = ' disabled ';
                if (User::canRoute($url) && $model->billing_lock_bmc == 0) {
                    $popupClass = ' generalGridConfirmationPopup ';
                }
                $popupWindowTitle = 'Are you sure you want to ' . ($model->data_lock_bmc == 1 ? 'Unlock' : 'Lock') . ' data for BMC(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                $options = [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => $title,
                    'data-popup-message' => $popupWindowTitle,
                    'class' => $popupClass,
                    'data-post-url' => Url::to([$url, 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                ];
                return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
            },
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'text-center'];
            },
            'label' => Yii::t('app', 'Data Lock - BMC'), 'filter' => false];

        $appModel->fields['sync_lock_bmca'] = ['view' => ['grid'], 'value' => function($model) {
                $class = $model->sync_lock_bmc == 1 ? 'fa-lock' : 'fa-unlock';
                $title = $model->sync_lock_bmc == 1 ? 'Sync Unlock - BMC' : 'Sync Lock - BMC';
                $url = $model->data_lock_bmc == 1 ? '/payment/tbl-payment-cycle/bmc-sync-unlock' : '/payment/tbl-payment-cycle/bmc-sync-lock';
                $popupClass = ' disabled ';
                if (User::canRoute($url) && $model->billing_lock_bmc == 0) {
                    $popupClass = ' generalGridConfirmationPopup ';
                }
                $popupWindowTitle = 'Are you sure you want to ' . ($model->sync_lock_bmc == 1 ? 'Unlock' : 'Lock') . ' Sync for BMC(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                $options = [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => $title,
                    'data-popup-message' => $popupWindowTitle,
                    'class' => $popupClass,
                    'data-post-url' => Url::to([$url, 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                ];
                return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
            },
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'text-center'];
            },
            'label' => Yii::t('app', 'Sync Lock - BMC'), 'filter' => false];

        $appModel->fields['billing_lock_bmca'] = ['view' => ['grid'], 'value' => function($model) {
                $class = $model->billing_lock_bmc == 1 ? 'fa-lock' : 'fa-unlock';
                $title = $model->billing_lock_bmc == 1 ? 'Billing Unlock - BMC' : 'Billing Lock - BMC';
                $popupWindowTitle = 'Are you sure you want to ' . ($model->billing_lock_bmc == 1 ? 'Unlock' : 'Lock') . ' Billing for BMC(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                $options = [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => $title,
                    'data-popup-message' => $popupWindowTitle,
                    'class' => ' disabled ',
                    'data-post-url' => Url::to(['/payment/tbl-payment-cycle/bmc-billing-lock', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                ];
                return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
            },
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'text-center'];
            },
            'label' => Yii::t('app', 'Billing Lock - BMC'), 'filter' => false];
        $appModel->fields['data_lock_membera'] = ['view' => ['grid'], 'value' => function($model) {
                $class = $model->data_lock_member == 1 ? 'fa-lock' : 'fa-unlock';
                $title = $model->data_lock_member == 1 ? 'Data Unlock - Member' : 'Data Lock - Member';
                $url = $model->data_lock_member == 1 ? '/payment/tbl-payment-cycle/member-data-unlock' : '/payment/tbl-payment-cycle/member-data-lock';
                $popupClass = ' disabled ';
                if (User::canRoute($url) && $model->billing_lock_member == 0) {
                    $popupClass = ' generalGridConfirmationPopup ';
                }
                $popupWindowTitle = 'Are you sure you want to ' . ($model->data_lock_member == 1 ? 'Unlock' : 'Lock') . ' data for Member(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                $options = [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => $title,
                    'data-popup-message' => $popupWindowTitle,
                    'class' => $popupClass,
                    'data-post-url' => Url::to([$url, 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                ];
                return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
            },
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'text-center'];
            },
            'label' => Yii::t('app', 'Data Lock - Member'), 'filter' => false];

        $appModel->fields['sync_lock_membera'] = ['view' => ['grid'], 'value' => function($model) {
                $class = $model->sync_lock_member == 1 ? 'fa-lock' : 'fa-unlock';
                $title = $model->sync_lock_member == 1 ? 'Sync Unlock - Member' : 'Sync Lock - Member';
                $url = $model->sync_lock_member == 1 ? '/payment/tbl-payment-cycle/member-sync-unlock' : '/payment/tbl-payment-cycle/member-sync-lock';
                $popupClass = ' disabled ';
                if (User::canRoute($url) && $model->billing_lock_member == 0) {
                    $popupClass = ' generalGridConfirmationPopup ';
                }
                $popupWindowTitle = 'Are you sure you want to ' . ($model->sync_lock_member == 1 ? 'Unlock' : 'Lock') . ' Sync for Member(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                $options = [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-original-title' => $title,
                    'data-popup-message' => $popupWindowTitle,
                    'class' => $popupClass,
                    'data-post-url' => Url::to([$url, 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                ];
                return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
            },
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'text-center'];
            },
            'label' => Yii::t('app', 'Sync Lock - Member'), 'filter' => false];

        if (User::canRoute('/payment/tbl-payment-cycle/member-billing-lock')) {
            $appModel->fields['billing_lock_membera'] = ['view' => ['grid'], 'value' => function($model) {
                    $class = $model->billing_lock_member == 1 ? 'fa-lock' : 'fa-unlock';
                    $title = $model->billing_lock_member == 1 ? 'Billing Unlock - Member' : 'Billing Lock - Member';
                    $popupClass = ' disabled ';
                    if ($model->billing_lock_member == 0) {
                        $popupClass = ' generalGridConfirmationPopup ';
                    }
                    $popupWindowTitle = 'Are you sure you want to ' . ($model->billing_lock_member == 1 ? 'Unlock' : 'Lock') . ' Billing for Member(' . $model->getName($model->applicable_for) . '-' . $model->applicable_code . ')';
                    $options = [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => $title,
                        'data-popup-message' => $popupWindowTitle,
                        'class' => $popupClass,
                        'data-post-url' => Url::to(['/payment/tbl-payment-cycle/member-billing-lock', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code])
                    ];
//                        return '<i class="fa ' . $class . '"></i>';
                    return GhostHtml::a_alert('<i class="fa ' . $class . '"></i>', ['#', 'payment_cycle_code' => $model->payment_cycle_code, 'id' => $model->payment_cycle_applicabilty_code], $options);
                },
                'format' => 'raw',
                'contentOptions' => function($model) {
                    return ['class' => 'text-center'];
                },
                'label' => Yii::t('app', 'Billing Lock - Member'), 'filter' => false];
        }
        $appModel->actions = [];

        return $appModel->customerTypeWiseApplicability();
    }

    public function updateRecords($payment_cycle_code, $id, $updateField, $lockMessage, $unlockMessage) {
        $model = new TblPaymentCycleApplicability();
        $model->payment_cycle_applicabilty_code = $id;
        $modelData = $model->getData();
        $historyModel = new TblPaymentCycleApplicabilityHistory();
        Yii::$app->operation->history($modelData, $historyModel, UPDATE);
        $title = $modelData->{$updateField} == 1 ? $unlockMessage : $lockMessage;
        $modelData->{$updateField} = $modelData->{$updateField} == 1 ? 0 : 1;
        if (in_array($updateField, ['billing_lock_member', 'data_lock_member', 'data_lock_bmc'])) {
            if ($updateField == 'billing_lock_member' && $modelData->{$updateField} == 1) {
                $modelData->sync_lock_member = 1;
                $modelData->data_lock_member = 1;
            } else if ($updateField == 'data_lock_member' && $modelData->{$updateField} == 1) {
                $modelData->sync_lock_member = 1;
            } else if ($updateField == 'data_lock_bmc' && $modelData->{$updateField} == 1) {
                $modelData->sync_lock_bmc = 1;
            }
        }
        $modelData->scenario = 'lockUnlock';
        $transaction = $this->generalModel->saveTransaction([$modelData, $historyModel], [$title, 'edit']);
        return $this->redirect(['payment-cycle-applicability', 'id' => $payment_cycle_code]);
    }

    public function actionBmcDataLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'data_lock_bmc', 'Data Lock - BMC', 'Data Unlock - BMC');
    }

    public function actionMemberDataLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'data_lock_member', 'Data Lock - Member', 'Data Unlock - Member');
    }

    public function actionBmcSyncLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'sync_lock_bmc', 'Sync Lock - BMC', 'Sync Unlock - BMC');
    }

    public function actionMemberSyncLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'sync_lock_member', 'Sync Lock - Member', 'Sync Unlock - Member');
    }

    public function actionBmcBillingLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'billing_lock_bmc', 'Billing Lock - BMC', 'Billing Unlock - BMC');
    }

    public function actionMemberBillingLock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'billing_lock_member', 'Billing Lock - Member', 'Billing Unlock - Member');
    }

    public function actionDeleteApp() {
        $id = Yii::$app->request->post('id');
//        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_payment_cycle_applicability', $id, 'payment_cycle_applicability_code']);
//        if ($valueOut == 0) {
        $model = new TblPaymentCycleApplicability();
        $model->payment_cycle_applicabilty_code = $id;
        $modelData = $model->getData();
        $historyModel = new TblPaymentCycleApplicabilityHistory();
        Yii::$app->operation->history($modelData, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$modelData, $historyModel]);
//        } else {
//            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
//        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionPaymentCycleList() {
        $out = null;

        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            if (!empty($value[0]) && !empty($value[1])) {
                $unionCode = $value[0];
                $code = $value[1];
                $type = $value[2];
                $for = $value[3];
                $where = (array) json_decode($value[4]);
                $member_billing_lock_check = isset($value[5]) ? $value[5] : '0';
                $typeCheck = isset($value[6]) ? $value[6] : 0;
                $paymentcycleModel = new TblPaymentCycleApplicability();
                $list = $paymentcycleModel->paymentCycles($unionCode, $code, $type, $for, $where, $member_billing_lock_check, $typeCheck);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
            }
        }
        return Json::encode(['output' => '']);
    }

    public function actionPaymentCycleListWithDate() {
        $out = null;

        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            if (!empty($value[0]) && !empty($value[1])) {
                $unionCode = $value[0];
                $code = $value[1];
                $type = $value[2];
                $for = $value[3];
                $where = (array) json_decode($value[4]);
                $member_billing_lock_check = isset($value[5]) ? $value[5] : '0';
                $typeCheck = isset($value[6]) ? $value[6] : 0;
                $paymentcycleModel = new TblPaymentCycleApplicability();
                $list = $paymentcycleModel->paymentCycles($unionCode, $code, $type, $for, $where, $member_billing_lock_check, $typeCheck);
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $r,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
            }
        }
        return Json::encode(['output' => '']);
    }

    public function actionBmcDataUnlock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'data_lock_bmc', 'Data Lock - BMC', 'Data Unlock - BMC');
    }

    public function actionMemberDataUnlock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'data_lock_member', 'Data Lock - Member', 'Data Unlock - Member');
    }

    public function actionBmcSyncUnlock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'sync_lock_bmc', 'Sync Lock - BMC', 'Sync Unlock - BMC');
    }

    public function actionMemberSyncUnlock($payment_cycle_code, $id) {
        $this->updateRecords($payment_cycle_code, $id, 'sync_lock_member', 'Sync Lock - Member', 'Sync Unlock - Member');
    }

    public function actionUnionPaymentCycleList() {
        $out = null;

        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $paymentcycleModel = new TblPaymentCycle();
            $list = $paymentcycleModel->UnionPaymentCycles($unionCode);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionBulkDataLockUnlock() {
        $searchModel = new TblPaymentCycleApplicabilitySearch();
        $dataProvider = $searchModel->datasearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'lock-unlock-bulk';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $where['payment_cycle_applicabilty_code'] = $data[0];
                    $setData = $data[1];
                    $updateField = $data[2];
                    $existData = TblPaymentCycleApplicability::find()->where($where)->one();
                    $historyModel = new TblPaymentCycleApplicabilityHistory();
                    Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                    $saveModel[] = $historyModel;
                    $existData->{$updateField} = $setData;
                    $existData->scenario = 'lockUnlock';
                    $saveModel[] = $existData;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Data Status', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('_bulk_lock_unlock', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
