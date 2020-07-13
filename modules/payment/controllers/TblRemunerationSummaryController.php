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

class TblRemunerationSummaryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['remuneration-payment-cycle'];

    public function actionCreate() {
        $model = new TblRemunerationSummary();
        $model->scenario = 'processpayment';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $validate = TRUE;
            if ($_POST['warning'] == 0) {
                $validate = $model->ValidateDate('from_datetime', NULL, TRUE);
                if (!$validate) {
                    $message = \Yii::t('app', "Payment has been already processed.Are you sure you want to continue?");
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'confirm',
                        'hidden_field' => 'warning',
                        'message' => $message,
                    ]);
                }
            }
            if ($validate) {
                $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
                $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);
                $this->getRemunerationSpData($model);
                return $this->redirect(['tbl-vsp-payment/payment-adjust', 'TblVspPayment' => ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code, 'billing_type' => 'remuneration', 'customer_type' => 'DCS']]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    private function getRemunerationSpData($model) {
        $data = [];
        $data['from_datetime'] = $model->from_datetime;
        $data['to_datetime'] = $model->to_datetime;
        $data['union_code'] = $model->union_code;
        $data['bmc_code'] = $model->bmc_code;
        $data['plant_code'] = $model->plant_code;
        $data['mcc_plant_code'] = $model->mcc_plant_code;
        $data['calculate_milk_recovey'] = $model->calculate_milk_recovey;
        $data['calculate_other_head'] = $model->calculate_other_head;
        return Yii::$app->ClientPaymentConfig->processPayment('remuneration_payment', $data);

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
        $query = $model->find()->where(['from_datetime' => $model->from_datetime,
            'to_datetime' => $model->to_datetime,
            'bmc_code' => $model->bmc_code,
            'union_code' => $model->union_code,
            'billing_type' => 'remuneration',
            'status' => ['processed', 'rejected']]);

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
                                'status' => ['processed', 'rejected']])
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
                    'status' => ['processed', 'rejected']])
                ->all();
        $PaymentApp = TblRemunerationSummary::find()
                ->where(['from_datetime' => $model->from_datetime,
                    'to_datetime' => $model->to_datetime,
                    'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code])
                ->one();
        if (!empty($PaymentApp)) {
            $PaymentApp->status = 'sent';
            $save_model[] = $PaymentApp;
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
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Locked Successfully', 'info']);
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
                    'tbl_vsp_payment.status' => ['processed', 'rejected']])
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
            if (!empty($parents[0]) && !empty($parents[1])) {
                $model = new TblRemunerationSummary();
                $data = $model->RemunerationPaymentCycle($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
