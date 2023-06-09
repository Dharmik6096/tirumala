<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblRemunerationSummary;
use app\modules\payment\models\TblRemunerationSummaryHistory;
use app\modules\payment\models\TblVspPayment;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\payment\models\TblVspOutstanding;
use app\modules\payment\models\TblVspOutstandingHistory;
use PHPExcel;
use yii\data\ArrayDataProvider;
use app\modules\payment\models\TblPaymentStop;
use yii\widgets\ActiveForm;
use yii\web\Response;

class TblRemunerationSummaryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['remuneration-payment-cycle', 'process-remuneration'];

    public function actionCreate() {
        $model = new TblRemunerationSummary();
        if ($model->load(Yii::$app->request->post())) {
            $result = 'success';
            $model->scenario = 'processpayment';
            $multiple_bmc = FALSE;
            $bmc_array = [];
            $bmc_array[] = $model->bmc_code;
            $mcc_data = $model->mccPlantCode;
            if (!empty($mcc_data) && $mcc_data->vendor_payment_with_multiple_bmc == 1) {
                $model->bmc_code = $model->p_bmc_code;
                $multiple_bmc = TRUE;
                $bmc_array = $model->p_bmc_code;
            }
            if ($model->validate()) {
                $validate = $model->ValidateDate('from_datetime', NULL, TRUE);
                $queryParam = [];
                $queryParam[] = 'process-remuneration';
                $queryParamRegenerate = [];
                $queryParam['TblRemunerationSummary'] = ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime,
                    'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code, 'calculate_milk_recovey' => $model->calculate_milk_recovey,
                    'calculate_other_head' => $model->calculate_other_head,
                    'stop_payment_only' => 0];
                $queryParamRegenerate = $queryParam;
                $queryParamRegenerate['reGenerate'] = 0;
                $msg = '';
                if (!$validate) {
                    $result = 'displayConfirmPopup';
                    $msg = \Yii::t('app', "Payment has been already generated for selected Period.Do You want to Regenerate?");
                    $queryParamRegenerate['reGenerate'] = 1;
                } else {
                    $queryParam['reGenerate'] = 1;
                }
                $url = Url::to($queryParam);
                $url_regenerate = Url::to($queryParamRegenerate);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return ['status' => $result, 'url' => $url, 'url_regenerate' => $url_regenerate, 'msg' => $msg];
            } else {
                $form_validation = ActiveForm::validate($model);
                $model->bmc_code = is_array($model->bmc_code) ? NULL : $model->bmc_code;
                $model->scenario = 'default';
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $form_validation;
            }
            $model->scenario = 'default';
        }
        return $this->render('create', [
                    'model' => $model, 'post_url' => Url::to(['create'])
        ]);
    }

    public function actionCreateStopPayment() {
        $model = new TblRemunerationSummary();
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->payment_cycle_code)) {
                $payment_cycle_code = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $payment_cycle_code[0];
                $model->to_datetime = $payment_cycle_code[1];
            }
            $model->stop_payment_only = 1;
            $model->scenario = 'processpaymentstop';
            $multiple_bmc = FALSE;
            $bmc_array = [];
            $bmc_array[] = $model->bmc_code;
            $mcc_data = $model->mccPlantCode;
            if (!empty($mcc_data) && $mcc_data->vendor_payment_with_multiple_bmc == 1) {
                $model->bmc_code = $model->p_bmc_code;
                $multiple_bmc = TRUE;
                $bmc_array = $model->p_bmc_code;
            }
            if ($model->validate()) {
                $stopModel = new TblPaymentStop();
                $stopModel->bmc_code = $model->bmc_code;
                $stopModel->customer_type = 'DCS';
                $stopModel->payment_type = 'VENDOR_PAYMENT';
                $stopModel->is_remuneration = 1;
                $stopMsg = $stopModel->getStatusStop();
                if (!empty($stopMsg)) {
                    $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
                    $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);
                    $this->getRemunerationSpData($model);
                    $model->bmc_code = $bmc_array;
                    return $this->redirect(['tbl-vsp-payment/payment-adjust', 'TblVspPayment' => ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'mcc_plant_code' => $model->mcc_plant_code, 'union_code' => $model->union_code, 'billing_type' => 'remuneration', 'customer_type' => 'DCS', 'multiple_bmc' => $multiple_bmc]]);
                } else {
                    $msg = Yii::t('app', 'Stop Payment data is not available');
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'message' => $msg,
                    ]);
                }
            }
            $model->scenario = 'default';
        }
        return $this->render('create_stop_payment', [
                    'model' => $model,
        ]);
    }

    public function actionProcessRemuneration($reGenerate = 0) {
        if (Yii::$app->request->get()) {
            $model = new TblRemunerationSummary();
            $model->load(Yii::$app->request->get());
            $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
            $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);
            if ($reGenerate == 1) {
                $this->getRemunerationSpData($model);
            }
            $multiple_bmc = FALSE;
            $mcc_data = $model->mccPlantCode;
            if (!empty($mcc_data) && $mcc_data->vendor_payment_with_multiple_bmc == 1) {
                $multiple_bmc = TRUE;
            }
            return $this->redirect(['tbl-vsp-payment/payment-adjust', 'TblVspPayment' => ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'mcc_plant_code' => $model->mcc_plant_code, 'union_code' => $model->union_code, 'billing_type' => 'remuneration', 'customer_type' => 'DCS', 'multiple_bmc' => $multiple_bmc]]);
        }
    }

    private function getRemunerationSpData($model) {
        $bmc_array = [];
        $bmc_array[] = $model->bmc_code;
        if (is_array($model->bmc_code)) {
            $bmc_array = $model->bmc_code;
        }
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        foreach ($bmc_array as $bmc_code) {
            $data = [];
            $data['from_datetime'] = $model->from_datetime;
            $data['to_datetime'] = $model->to_datetime;
            $data['union_code'] = $model->union_code;
            $data['bmc_code'] = $bmc_code;
            $data['plant_code'] = $model->plant_code;
            $data['mcc_plant_code'] = $model->mcc_plant_code;
            $data['calculate_milk_recovey'] = $model->calculate_milk_recovey;
            $data['calculate_other_head'] = $model->calculate_other_head;
            $data['process_stop_payment'] = $model->stop_payment_only;
            $data['user_code'] = $user;
            Yii::$app->ClientPaymentConfig->processPayment('remuneration_payment', $data);
        }
        /* $result = \Yii::$app->db->createCommand("{CALL sp_remuneration_payment (:union_code,:plant_code,:mcc_plant_code,:bmc_code,:from_date,:to_date,:calculate_milk_recovey,:calculate_other_head)}")
          ->bindValue(':from_date', $model->from_datetime)
          ->bindValue(':to_date', $model->to_datetime)
          ->bindValue(':union_code', $model->union_code)
          ->bindValue(':bmc_code', $model->bmc_code)
          ->bindValue(':plant_code', $model->plant_code)
          ->bindValue(':mcc_plant_code', $model->mcc_plant_code)
          ->bindValue(':calculate_milk_recovey', $model->calculate_milk_recovey)
          ->bindValue(':calculate_other_head', $model->calculate_other_head);
          $query = $result->execute();
          return $query; */
    }

    public function actionPaymentDisburse() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());

        if (!empty($model->payment_cycle_code)) {
            $payment_cycle_code = explode('#', $model->payment_cycle_code);
            $model->from_datetime = $payment_cycle_code[0];
            $model->to_datetime = $payment_cycle_code[1];
        }
        $mcc_data = $model->mccPlantCode;
        if (!empty($mcc_data) && $mcc_data->vendor_payment_with_multiple_bmc == 1) {
            $model->bmc_code = $model->p_bmc_code;
        }
        $query = $model->find()->where(['from_datetime' => $model->from_datetime,
            'to_datetime' => $model->to_datetime,
            'bmc_code' => $model->bmc_code,
            'union_code' => $model->union_code,
            'billing_type' => 'remuneration',
            'status' => ['locked', 'rejected']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Remuneration Payment Disburse : Step 1'
        ]);
    }

    public function actionConfirmPayment() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        if (Yii::$app->request->post()) {
            $model = new TblVspPayment();
            $model->load(Yii::$app->request->post());
            $model->customer_type = 'DCS';
            $model->billing_type = 'remuneration';
            if (!empty($model->payment_cycle_code)) {
                $payment_cycle_code = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $payment_cycle_code[0];
                $model->to_datetime = $payment_cycle_code[1];
                if (Yii::$app->request->post('flag') == 'vsp') {

                    $query = $model->find()->where(['from_datetime' => $model->from_datetime,
                                'to_datetime' => $model->to_datetime,
                                'bmc_code' => $model->bmc_code,
                                'union_code' => $model->union_code,
                                'billing_type' => 'remuneration',
                                'status' => ['locked', 'rejected']])
                            ->all();

                    $pay_cnt = count($query);
                    $pay_amount = array_sum(array_column($query, 'final_pay'));
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $query,
                        'pagination' => FALSE
                    ]);
                    $tot_cnt = count($query);
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Out of (<b>' . $tot_cnt . '</b>) Remuneration Payment of (<b>' . $pay_cnt . '</b>)  Vendor will be only done.<br/>Total Payable :: <b>' . $pay_amount . '</b>']);

                    return $this->render('@app/modules/payment/views/tbl-vsp-payment/confirm-payment', [
                                'pay_amount' => $pay_amount,
                                'searchModel' => $model,
                                'dataProvider' => $dataProvider,
                    ]);
                } else {
                    if ($this->exportCSV($model)) {
                        return $this->redirect(Url::previous());
                    }
                }
            }
        }
    }

    public function actionBankPayment() {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->post());
        $this->LockBilling($model);
        return $this->redirect(['tbl-vsp-payment/index']);
    }

    protected function LockBilling($model) {
        $payment_cycle_code = explode('#', $model->payment_cycle_code);
        $model->from_datetime = $payment_cycle_code[0];
        $model->to_datetime = $payment_cycle_code[1];
        $save_model = [];
        $newModel = new TblVspPayment();
        $query = $newModel->find()
                ->where(['from_datetime' => $model->from_datetime,
                    'to_datetime' => $model->to_datetime,
                    'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code,
                    'billing_type' => 'remuneration',
                    'status' => ['locked', 'rejected']])
                ->all();

        $pendingCount = $newModel->find()
                ->where(['from_datetime' => $model->from_datetime,
                    'to_datetime' => $model->to_datetime,
                    'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code,
                    'billing_type' => 'remuneration'])
                ->andWhere(['IN', 'status', ['generated', 'processed']])
                ->count();
        if ($pendingCount == 0) {
            $PaymentApp = TblRemunerationSummary::find()
                    ->where(['from_datetime' => $model->from_datetime,
                        'to_datetime' => $model->to_datetime,
                        'bmc_code' => $model->bmc_code,
                        'union_code' => $model->union_code,
                        'status' => ['locked']])
                    ->all();
            foreach ($PaymentApp as $dataApp) {
                $dataApp->status = 'sent';
                $save_model[] = $dataApp;
            }
        }
        foreach ($query as $data) {
            $outstanding = TblVspOutstanding::find()->where([
                        'customer_type' => $data->customer_type,
                        'customer_code' => $data->customer_code
                    ])->one();
            if (empty($outstanding)) {
                $outstanding = new TblVspOutstanding();
                $outstanding->attributes = $data->attributes;
            } else {
                $oshistoryModel = new TblVspOutstandingHistory();
                Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
                $save_model[] = $oshistoryModel;
            }
            $outstanding->transaction_date = date('Y-m-d');
            $outstanding->hold_amount = $data->hold_amount;
            $outstanding->due_amount = $data->adjust_amount;
            $save_model[] = $outstanding;
            $data->scenario = 'remuneration';
            $data->status = 'sent';
            $save_model[] = $data;
        }
        $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Locked Successfully', 'info']);
        if ($transaction == 'customRedirect') {
            $bmc_array = [];
            $bmc_array[] = $model->bmc_code;
            if (is_array($model->bmc_code)) {
                $bmc_array = $model->bmc_code;
            }
            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            foreach ($bmc_array as $bmc_code) {
                $param = [];
                $param['from_datetime'] = $model->from_datetime;
                $param['customer_type'] = 'DCS';
                $param['bmc_code'] = $bmc_code;
                $param['user_code'] = $user;
                Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
            }
        }
    }

    protected function exportCSV($model) {
        $newModel = new TblVspPayment();
        $query = $newModel->find()
                ->where(['tbl_vsp_payment.from_datetime' => $model->from_datetime,
                    'tbl_vsp_payment.to_datetime' => $model->to_datetime,
                    'tbl_vsp_payment.bmc_code' => $model->bmc_code,
                    'tbl_vsp_payment.union_code' => $model->union_code,
                    'tbl_vsp_payment.billing_type' => 'remuneration',
                    'tbl_vsp_payment.status' => ['locked', 'rejected']])
                ->joinWith(['dcsCode'])
                ->all();

        $header = [
            'mime' => 'application/csv',
            'extension' => 'csv',
            'writer' => 'CSV',
        ];

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getDefaultStyle()
                ->getNumberFormat()
                ->setFormatCode(
                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
        );
        $rowCount = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Vendor Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Vendor Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Account No');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Bank');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Branch');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'IFSC');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Total Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Adjsut Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Final Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Adjsut Remarks');
        foreach ($query as $row) {
            // if ($row->final_pay > 0) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->customer_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getCustomer($row, $row->customer_type));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '="' . $row->bank_account_no . '"');
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->bank_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->branch_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->ifsc);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->adjust_amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->final_pay);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->adjust_remark);
            //  }
        }
        $fileName = "payment_disburse_vsp." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionRemunerationPaymentCycle() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && (!empty($parents[1]) || !empty($parents[2]))) {
                $bmc_array = !empty($parents[2]) ? $parents[2] : [];
                if (!empty($parents[1])) {
                    $bmc_array[] = $parents[1];
                }
                $model = new TblRemunerationSummary();
                $data = $model->RemunerationPaymentCycle($parents[0], $bmc_array);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
