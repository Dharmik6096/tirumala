<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\modules\payment\models\TblDcsPaymentCycleSearch;
use app\modules\payment\models\TblDcsPaymentCycleHistory;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblDcsPaymentCycleApplicabilitySearch;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use DateTime;
use ReflectionClass;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblDcsPaymentCycleController implements the CRUD actions for TblDcsPaymentCycle model.
 */
class TblDcsPaymentCycleController extends ChildController {

    /**
     * Lists all TblDcsPaymentCycle models.
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionIndex() {
        $searchModel = new TblDcsPaymentCycleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDcsPaymentCycle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDcsPaymentCycle();
        $this->viewFile = 'create';
//        if ($this->model->load(Yii::$app->request->post())) {
//            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT);
//            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT);
//            $this->model->union_code = Yii::$app->session->get('organizations_code');
//            $transaction = $this->generalModel->saveTransaction([$this->model], ['Payment Cycle', 'create']);
//            if ($transaction !== FALSE) {
//                return $this->{$transaction}();
//            }
//        }
        if ($this->model->load(Yii::$app->request->post())) {
            //$this->model->union_code = Yii::$app->session->get('Unions');
//            var_dump($this->model);exit;
            $this->model->is_active = 1;
            $this->model->is_billing = 0;
            $this->model->lock_billing_process = 0;
            $this->model->lock_data = 0;
            $shift_from_time = '06:00:00';
            $shift_to_time = '06:00:00';
            $shift_from_type = 1;
            $shift_to_type = 1;
            if ($this->model->from_shift == 2) {
                $shift_from_time = '16:00:00';
                $shift_from_type = 2;
            }
            if ($this->model->to_shift == 2) {
                $shift_to_time = '16:00:00';
                $shift_to_type = 2;
            }

            $from_date = new DateTime($this->model->from_date);
//            var_dump($this->model->dcs_payment_cycle_code);exit;
            $to_date = new DateTime($this->model->to_date);
            $this->model->from_date = $from_date->format('Y-m-d') . ' ' . $shift_from_time;
            $this->model->to_date = $to_date->format('Y-m-d') . ' ' . $shift_to_time;

            if ($this->model->validate()) {
                $save_model = [];
                // $from_date = new DateTime($this->model->from_date);
                // $to_date = new DateTime($this->model->to_date);
                for ($i = 0; $from_date < $to_date; $i++) {
                    $shift_from_date = $from_date->format('Y-m-d');
                    $new_model = new ReflectionClass($this->model->className());
                    $model = $new_model->newInstanceArgs();
                    $data = $this->model->attributes;
                    $model->setAttributes($data);
                    $model->dcs_payment_cycle_code = $this->model->getCode() + $i;

                    //  $model->from_date = $from_date->format('Y-m-d') . ' ' . $from_time;
                    //  if (in_array($this->model->interval_value, ['5', '10', '15'])) {
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
                        $to_time = '16:00:00';
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
                        $to_time = '16:00:00';
                    }
                    $model->from_date = $shift_from_date . ' ' . $from_time;
                    $model->to_date = $end_date->format('Y-m-d') . ' ' . $to_time;
                    $model->from_shift = $from_shift;
                    $model->to_shift = $to_shift;
                    $interval = date_diff(new DateTime($model->from_date), new DateTime($model->to_date));
                    $model->interval_value = $interval->days + 1;
                    $save_model[] = $model;
//                    var_dump($model);exit;
                }
                $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Cycle - Society', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDcsPaymentCycle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblDcsPaymentCycleHistory();
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
     * Deletes an existing TblDcsPaymentCycle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_dcs_payment_cycle', Yii::$app->request->post('id'), 'dcs_payment_cycle_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblDcsPaymentCycleHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionPaymentCycleApplicability($id) {
        $cmodel = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblDcsPaymentCycleApplicability();
        $appModel->searchModel = new TblDcsPaymentCycleApplicabilitySearch();
        $appModel->field_name = 'dcs_payment_cycle_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'payment cycle applicability';
        $appModel->top_section = FALSE;
        $appModel->is_union = FALSE;
        $appModel->union_code = $cmodel->union_code;
        $appModel->payment = true;
        $appModel->title = Yii::$app->controls->view_date($cmodel->from_date) . ' to ' . Yii::$app->controls->view_date($cmodel->to_date);
        $appModel->fields = ['from_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
            'to_date' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
            'dcs_code' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],
            'is_lock' => ['view' => ['grid']]
        ];
        $appModel->dcs_filters = ['society' => 'Society', 'routes' => 'Routes', 'mcc' => 'MCC'];
        return $appModel->paymentApplicability();
    }

    /**
     * Finds the TblDcsPaymentCycle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsPaymentCycle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsPaymentCycle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLockPaymentCycle() {
        $flag = Yii::$app->request->post('flag');
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        $text = '';
        $childModels = [];
        if (!empty($this->model) && !empty($flag)) {
            $historyModel = new TblDcsPaymentCycleHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            if ($flag == 'billing') {
                $text = 'billing';
                $this->model->lock_billing_process = 1;
                $childModels = $this->setChildModels($this->model, 'is_lock');
            } else if ($flag == 'data') {
                $text = 'data';
                $this->model->lock_data = 1;
                $childModels = $this->setChildModels($this->model, 'data_lock');
            }
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $childModels, ['Payment Cycle', 'edit']);
            if ($transaction !== FALSE) {
                $record = ['status' => 'success', 'msg' => 'Payment Cycle ' . $text . ' locked successfully.'];
                return Json::encode($record);
            }
        }
        $record = ['status' => 'error', 'msg' => 'Payment Cycle ' . $text . ' could not be locked.'];
        return Json::encode($record);
    }

    private function setChildModels($model, $attr) {
        $appModels = $model->applicabilities;
        $childModels = [];
        if (!empty($appModels)) {
            foreach ($appModels as $appModel) {
                $appModel->{$attr} = 1;
                $childModels[] = $appModel;
            }
        }
        return $childModels;
    }

}
