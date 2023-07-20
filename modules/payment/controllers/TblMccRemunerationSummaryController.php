<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMccRemunerationSummary;
use app\modules\payment\models\TblMccPayment;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TblMccRemunerationSummaryController extends \app\controllers\ChildController {

    public $freeAccessActions = ['remuneration-payment-cycle'];

    public function actionCreate() {
        $model = new TblMccRemunerationSummary();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'processpayment';
            $mcc_array = [];
            $bmc_array = [];
            $mcc_array = $model->mcc_plant_code;
            $bmc_array = $model->bmc_code;

            if ($model->validate()) {
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
                    $validate = $model->ValidateDate('from_datetime', NULL, FALSE);
                }
                if ($validate) {
                    $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
                    $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);
                    $this->getRemunerationSpData($model);
                    $model->mcc_plant_code = $mcc_array;
                    $model->bmc_code = $bmc_array;
                    return $this->redirect(['tbl-mcc-payment/payment-adjust', 'TblMccPayment' => ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'mcc_plant_code' => $model->mcc_plant_code, 'union_code' => $model->union_code, 'billing_type' => 'mcc_remuneration', 'customer_type' => 'DCS']]);
                }
            }
            $model->scenario = 'default';
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
        $data['bmc_code'] = is_array($model->bmc_code) ? ',' . implode(',', $model->bmc_code) . ',' : $model->bmc_code;
        $data['plant_code'] = $model->plant_code;
        $data['mcc_plant_code'] = is_array($model->mcc_plant_code) ? ',' . implode(',', $model->mcc_plant_code) . ',' : $model->mcc_plant_code;
        $data['calculate_milk_recovey'] = $model->calculate_milk_recovey;
        $data['calculate_other_head'] = $model->calculate_other_head;
        Yii::$app->ClientPaymentConfig->processPayment('mcc_remuneration_payment', $data);

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
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        $model = new TblMccPayment();
        $model->load(Yii::$app->request->get());

        if (!empty($model->payment_cycle_code)) {
            $payment_cycle_code = explode('#', $model->payment_cycle_code);
            $model->from_datetime = $payment_cycle_code[0];
            $model->to_datetime = $payment_cycle_code[1];
        }

        $query = $model->find()->where(['CAST(from_datetime as date)' => date('Y-m-d', strtotime($model->from_datetime)),
            'CAST(to_datetime as date)' => date('Y-m-d', strtotime($model->to_datetime)),
            'bmc_code' => $model->bmc_code,
            'union_code' => $model->union_code,
            'billing_type' => 'mcc_remuneration',
            'status' => ['processed', 'rejected']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'MCC Payment Disburse : Step 1'
        ]);
    }

    public function actionConfirmPayment() {
        $this->layout = "@app/web/themes/emilk/layouts/paymentLayout.php";
        if (Yii::$app->request->post()) {
            $model = new TblMccPayment();
            $model->load(Yii::$app->request->post());
            $model->customer_type = 'DCS';
            $model->billing_type = 'mcc_remuneration';
            if (!empty($model->payment_cycle_code)) {
                $payment_cycle_code = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $payment_cycle_code[0];
                $model->to_datetime = $payment_cycle_code[1];

                if (Yii::$app->request->post('flag') == 'vsp') {
                    $query = $model->find()->where(['from_datetime' => $model->from_datetime,
                                'to_datetime' => $model->to_datetime,
                                'bmc_code' => $model->bmc_code,
                                'union_code' => $model->union_code,
                                'billing_type' => 'mcc_remuneration',
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
                        'message' => 'Out of (<b>' . $tot_cnt . '</b>) BMC Payment of (<b>' . $pay_cnt . '</b>)  BMC will be only done.<br/>Total Payable :: <b>' . $pay_amount . '</b>']);

                    return $this->render('@app/modules/payment/views/tbl-mcc-payment/confirm-payment', [
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

    protected function exportCSV($model) {
        $newModel = new TblMccPayment();
        $query = $newModel->find()
                ->where(['tbl_mcc_payment.from_datetime' => $model->from_datetime,
                    'tbl_mcc_payment.to_datetime' => $model->to_datetime,
                    'tbl_mcc_payment.bmc_code' => $model->bmc_code,
                    'tbl_mcc_payment.union_code' => $model->union_code,
                    'tbl_mcc_payment.billing_type' => 'mcc_remuneration',
                    'tbl_mcc_payment.status' => ['processed', 'rejected']])
                ->joinWith(['bmcCode'])
                ->all();

        $header = [
            'mime' => 'application/csv',
            'extension' => 'csv',
            'writer' => 'CSV',
        ];

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getDefaultStyle()
                ->getNumberFormat()
                ->setFormatCode(
                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
        );
        $rowCount = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'BMC Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'BMC Name');
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
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->bmc_code);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getforeignkey($row->bmcCode, 'bmc_name'));
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
        $fileName = "payment_disburse_mcc." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionRemunerationPaymentCycle() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && (!empty($parents[1]))) {
                $model = new TblMccRemunerationSummary();
                $data = $model->RemunerationPaymentCycle($parents[0], $parents[1]);
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
