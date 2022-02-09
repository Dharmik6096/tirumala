<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVspPayment;
use app\modules\payment\models\TblVspPaymentSearch;
use app\modules\payment\models\TblPaymentCycleApplicability;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPaymentTransactionSearch;
use app\modules\payment\models\TblVspPaymentHistory;
use PHPExcel;
use app\modules\payment\models\TblPaymentTransaction;
use app\modules\payment\models\TblUnionBankPayment;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblBillHeadDetail;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblVspPaymentTransaction;
use app\modules\vsp\models\TblMemberPaymentAllow;
use app\modules\payment\models\TblVspOutstanding;
use app\modules\payment\models\TblVspOutstandingHistory;
use yii\helpers\Url;
use app\modules\payment\models\TblVspPaymentRecovery;

/**
 * TblVspPaymentController implements the CRUD actions for TblVspPayment model.
 */
class TblVspPaymentController extends \app\controllers\ChildController {

    /**
     * Creates a new TblVspPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVspPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $searchModel = new TblVspPaymentTransactionSearch();
        $searchModel->vsp_payment_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new TblVspPayment();
        $model->scenario = 'processpayment';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
// $this->insertPaymentData($model);
            $this->getVspSpData($model);
            return $this->redirect(['payment-adjust', 'TblVspPayment' => ['payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type, 'union_code' => $model->union_code]]);
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());

        if (Yii::$app->request->post()) {
//        if (Yii::$app->request->post('TblVspPayment')) {
//            $postData = Yii::$app->request->post();
            $adjust_id = Yii::$app->request->post('TblVspPayment')['vsp_payment_code'];
            $adjust_amt = Yii::$app->request->post('TblVspPayment')['adjust_amount'];
            $adjust_remark = Yii::$app->request->post('TblVspPayment')['adjust_remark'];
            $hold_amt = Yii::$app->request->post('TblVspPayment')['hold_amount'];
            $save_model = [];
            $cnt = 0;
            foreach ($adjust_id as $key => $value) {
                if (($adjust_amt[$key] != 0 && $adjust_amt[$key] != '') || ($hold_amt[$key] != 0 && $hold_amt[$key] != '')) {
                    $data = TblVspPayment::findOne($adjust_id[$key]);
                    $updateData = false;
                    $oldData = $data->oldAttributes;
                    $historyModel = new TblVspPaymentHistory();
                    Yii::$app->operation->history($data, $historyModel, UPDATE);
                    $data->adjust_amount = $adjust_amt[$key];
                    $data->adjust_remark = $adjust_remark[$key];
                    $data->hold_amount = $hold_amt[$key];
                    $data->final_pay = (float) $data->net_payable + (float) $adjust_amt[$key] - (float) $hold_amt[$key];
                    if ($model->billing_type == 'remuneration') {
                        $data->scenario = 'remuneration';
                    }
                    if (!empty($oldData) && ($oldData['hold_amount'] != $data->hold_amount || $oldData['adjust_amount'] != $data->adjust_amount || $oldData['adjust_remark'] != $data->adjust_remark)) {
                        $updateData = true;
                    }
                    if ($updateData) {
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                        $cnt++;
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment of ' . $cnt . ' ' . Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') . '  adjusted succesfully', 'info']);

            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            $msg = '';
            $url = Url::to(['index']);
            if ($transaction == 'customRedirect') {
                $result = 'success';
            } else {
                $result = 'error';
                $msgData = Yii::$app->session->getFlash('success');
                $msg = !empty($msg['message']) ? $msg['message'] : '';
            }
            return ['status' => $result, 'url' => $url, 'msg' => $msg];
        }

        if ($model->billing_type == 'remuneration') {
            $query = $model->getRemunerationRecords();
            $title = 'Remuneration Payment Process : Step 2';
        } else {
            $query = $model->getRecords();
            $title = 'Vendor Payment Process : Step 2';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        return $this->render('payment-adjust', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => $title,
        ]);
    }

    public function actionBillHead() {
        if (!empty($_POST['code'])) {
            $searchModel = new TblVspPaymentTransactionSearch();
            $searchModel->vsp_payment_code = $_POST['code'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->renderAjax('bill-head-view', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /* To bind societies based on selected payment cycle */

    public function actionListSociety() {
        $paymentcycleModel = new TblDcsPaymentCycleApplicability();
        $society_list = $paymentcycleModel->societyList(Yii::$app->request->post('payment_cycle'), false);
        $member_payment = new TblMemberPaymentAllow();
        $society_list['member_payment'] = $member_payment->getMemberPaymentDcs(Yii::$app->request->post('payment_cycle'));
        $society_list['member_payment_msg'] = '';
        if (!empty($society_list['member_payment'])) {
            $message = 'Please Process Member Payment of following Society.<br/>';
            $message .= implode('<br/>', array_values($society_list['member_payment']));
            $society_list['member_payment_msg'] = $message;
            $society_list['member_payment'] = array_keys($society_list['member_payment']);
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $society_list]);
    }

    /**
     * Finds the TblVspPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVspPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVspPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function getVspSpData($model) {
        $paymentCycle = $model->paymentCycleCode;
        $data = [];
        $data['union_code'] = $model->union_code;
        $data['bmc_code'] = $model->bmc_code;
        $data['customer_type'] = $model->customer_type;
        $data['payment_cycle_code'] = $model->payment_cycle_code;
        $data['from_datetime'] = date('Y-m-d H:i:s', strtotime($paymentCycle->from_date));
        $data['from_shift'] = $paymentCycle->from_shift;
        $data['to_datetime'] = date('Y-m-d H:i:s', strtotime($paymentCycle->to_date));
        $data['to_shift'] = $paymentCycle->to_shift;
        /* delete recovery data */
        Yii::$app->db->createCommand("delete from tbl_vsp_payment_recovery
where payment_cycle_code = :payment_cycle_code and bmc_code=:bmc_code and customer_type = :customer_type")
                ->bindValue(':payment_cycle_code', $model->payment_cycle_code)
                ->bindValue(':bmc_code', $model->bmc_code)
                ->bindValue(':customer_type', $model->customer_type)
                ->execute();
        /* delete recovery data */
        return Yii::$app->ClientPaymentConfig->processPayment('vsp_payment', $data);
        /*
          $result = \Yii::$app->db->createCommand("{CALL sp_vsp_payment (:union_code,:from_date,:from_shift,:to_date,:to_shift,:payment_cycle_code,:bmc_code,:customer_type)}")
          ->bindValue(':from_date', date('Y-m-d H:i:s', strtotime($paymentCycle->from_date)))
          ->bindValue(':to_date', date('Y-m-d H:i:s', strtotime($paymentCycle->to_date)))
          ->bindValue(':from_shift', $paymentCycle->from_shift)
          ->bindValue(':to_shift', $paymentCycle->to_shift)
          ->bindValue(':union_code', $model->union_code)
          ->bindValue(':payment_cycle_code', $model->payment_cycle_code)
          ->bindValue(':bmc_code', $model->bmc_code)
          ->bindValue(':customer_type', $model->customer_type);
          $query = $result->execute();
          return $query; */
    }

    public function actionPaymentDisburse() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code,
            'bmc_code' => $model->bmc_code,
            'customer_type' => $model->customer_type,
            'status' => ['processed', 'rejected']]);
//  ->andWhere(['>', 'final_pay', 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Vendor Payment Disburse : Step 1'
        ]);
    }

    public function actionConfirmPayment() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        if (Yii::$app->request->post()) {
            $model = new TblVspPayment();
//   if (isset($_REQUEST['selection'])) {
            $model->load(Yii::$app->request->post());
            if (!empty($model->payment_cycle_code)) {
                if (Yii::$app->request->post('flag') == 'vsp') {
//  $model->dcs_code = Yii::$app->request->post('selection');
                    $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code,
                                'bmc_code' => $model->bmc_code,
                                'customer_type' => $model->customer_type,
                                'status' => ['processed', 'rejected']])
// ->andWhere(['>', 'tbl_vsp_payment.final_pay', 0])
                            ->all();

                    /*   if (Yii::$app->session->get('makerChecker') == 1) {
                      $payment_cnt = $model->find()->select(['dcs_code', 'final_pay' => 'round(final_pay,0)'])->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'dcs_code' => $model->dcs_code])
                      ->andWhere(['<>', 'ifsc', ''])->andWhere(['is not', 'ifsc', NULL])
                      ->andWhere(['<>', 'bank_account_no', ''])->andWhere(['is not', 'bank_account_no', NULL])
                      ->andWhere(['is_verified' => [1]])
                      ->asArray()
                      ->all();
                      } else {
                      $payment_cnt = $model->find()->select(['dcs_code', 'final_pay' => 'round(final_pay,0)'])->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'dcs_code' => $model->dcs_code])
                      ->andWhere(['<>', 'ifsc', ''])->andWhere(['is not', 'ifsc', NULL])
                      ->andWhere(['<>', 'bank_account_no', ''])->andWhere(['is not', 'bank_account_no', NULL])
                      ->andWhere(['is_verified' => [0, 1]])
                      ->asArray()
                      ->all();
                      } */
                    $pay_cnt = count($query);
                    $pay_amount = array_sum(array_column($query, 'final_pay'));
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $query,
                        'pagination' => FALSE
                    ]);
                    $tot_cnt = count($query);
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Out of (<b>' . $tot_cnt . '</b>) Vendor Payment of (<b>' . $pay_cnt . '</b>)  Vendor will be only done.<br/>Total Payable :: <b>' . $pay_amount . '</b>']);
                    return $this->render('confirm-payment', [
                                'pay_amount' => $pay_amount,
                                'searchModel' => $model,
                                'dataProvider' => $dataProvider,
                    ]);
                } else {
                    if ($this->exportCSV($model)) {
                        return $this->redirect(\yii\helpers\Url::previous());
                    }
                }
            }
//            } else {
//                return $this->redirect(\yii\helpers\Url::previous());
//            }
        }
    }

    protected function exportCSV($model) {
        $newModel = new TblVspPayment();
        $query = $newModel->find()->where(['tbl_vsp_payment.payment_cycle_code' => $model->payment_cycle_code,
                    'tbl_vsp_payment.bmc_code' => $model->bmc_code,
                    'tbl_vsp_payment.customer_type' => $model->customer_type,
                    'tbl_vsp_payment.status' => ['processed', 'rejected'],
                        // 'tbl_vsp_payment.dcs_code' => $_REQUEST['selection']
                ])
// ->andWhere(['>', 'tbl_vsp_payment.final_pay', 0])
                ->joinWith(['dcsCode', 'mainCustomerCode'])
                ->all();

        $extention = 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => 'Excel2007',
        ];

        $fileName = "payment_disburse_vsp." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>Vendor Code</td>";
        echo "<td>Vendor Name</td>";
        echo "<td>Account No</td>";
        echo "<td>Bank</td>";
        echo "<td>Branch</td>";
        echo "<td>IFSC</td>";
        echo "<td>Total Amount</td>";
        echo "<td>Adjsut Amount</td>";
        echo "<td>Final Amount</td>";
        echo "<td>Adjsut Remarks</td>";
        echo "</tr>";

        foreach ($query as $row) {
            echo "<tr>";
            echo $this->setVal($row->customer_code);
            echo $this->setVal(Yii::$app->general->getCustomer($row, $row->customer_type));
            echo $this->setVal($row->bank_account_no);
            echo $this->setVal($row->bank_name);
            echo $this->setVal($row->branch_name);
            echo $this->setVal($row->ifsc);
            echo $this->setVal($row->amount);
            echo $this->setVal($row->adjust_amount);
            echo $this->setVal($row->final_pay);
            echo $this->setVal($row->adjust_remark);
            echo "</tr>";
        }
        echo "</table>";
        exit();

//        $header = [
//            'mime' => 'application/csv',
//            'extension' => 'csv',
//            'writer' => 'CSV',
//        ];
//
//        $objPHPExcel = new PHPExcel();
//        $objPHPExcel->setActiveSheetIndex(0);
//        $objPHPExcel->getDefaultStyle()
//                ->getNumberFormat()
//                ->setFormatCode(
//                        \PHPExcel_Style_NumberFormat::FORMAT_TEXT
//        );
//        $rowCount = 1;
//        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Vendor Code');
//        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Vendor Name');
//        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Account No');
//        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Bank');
//        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Branch');
//        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'IFSC');
//        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Total Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Adjsut Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Final Amount');
//        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Adjsut Remarks');
//        foreach ($query as $row) {
//            // if ($row->final_pay > 0) {
//            $rowCount++;
//            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->customer_code);
//            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, Yii::$app->general->getCustomer($row, $row->customer_type));
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '="' . $row->bank_account_no . '"');
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->bank_name);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->branch_name);
//            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->ifsc);
//            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->amount);
//            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->adjust_amount);
//            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->final_pay);
//            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->adjust_remark);
//            //  }
//        }
//        $fileName = "payment_disburse_vsp." . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
//        header('Content-Disposition: attachment;filename=' . $fileName);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        exit();
    }

    public function actionBankPayment() {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->post());
        // $model->dcs_code = Yii::$app->request->post('selection');
        $this->LockBilling($model);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        $msg = '';
        $url = Url::to(['index']);
        $result = 'success';
        return ['status' => $result, 'url' => $url, 'msg' => $msg];
//        return $this->redirect(['index']);
    }

    protected function LockBilling($model) {
        $param = [];
        $param['customer_type'] = $model->customer_type;
        $param['bmc_code'] = $model->bmc_code;
        $param['applicable_for'] = 'BMC';
        $param['payment_cycle_code'] = $model->payment_cycle_code;
        $param['user_code'] = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        Yii::$app->ClientPaymentConfig->processPayment('vsp_payment_disburse', $param);

//        $save_model = [];
//        $newModel = new TblVspPayment();
//        $query = $newModel->find()->where([
//                    'payment_cycle_code' => $model->payment_cycle_code,
//                    'tbl_vsp_payment.bmc_code' => $model->bmc_code,
//                    'tbl_vsp_payment.customer_type' => $model->customer_type,
//                    'status' => ['processed', 'rejected'],
//                ])
//                ->all();
//        $PaymentApp = TblPaymentCycleApplicability::find()
//                ->where(['payment_cycle_code' => $model->payment_cycle_code,
//                    'applicable_code' => $model->bmc_code,
//                    'applicable_for' => 'BMC',
//                    'applicable_type' => $model->customer_type,
//                ])
//                ->one();
//        if (!empty($PaymentApp)) {
//            $model->from_datetime = $PaymentApp->from_date;
//            $PaymentApp->billing_lock_bmc = 1;
//            $save_model[] = $PaymentApp;
//        }
//        foreach ($query as $data) {
//            $outstanding = TblVspOutstanding::find()->where([
//                        'customer_type' => $data->customer_type,
//                        'customer_code' => $data->customer_code
//                    ])->one();
//            if (empty($outstanding)) {
//                $outstanding = new TblVspOutstanding();
//                $outstanding->attributes = $data->attributes;
//            } else {
//                $oshistoryModel = new TblVspOutstandingHistory();
//                Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
//                $save_model[] = $oshistoryModel;
//            }
//            // $outstanding->scenario = 'payment';
//            $outstanding->payment_cycle_code = $data->payment_cycle_code;
//            $outstanding->hold_amount = $data->hold_amount;
//            $outstanding->due_amount = $data->adjust_amount;
//            $save_model[] = $outstanding;
//            $data->status = 'sent';
//            $save_model[] = $data;
//            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Locked Successfully', 'info']);
//            if ($transaction == 'customRedirect') {
//                $param = [];
//                $param['from_datetime'] = $model->from_datetime;
//                $param['customer_type'] = $model->customer_type;
//                $param['bmc_code'] = $model->bmc_code;
//                Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
//            }
//        }
    }

    protected function exportTxt($model) {
        $send_notif = FALSE;
        try {
            $this->deletepayment($model);
            $union_bank = TblUnionBankPayment::find()->where(['union_code' => $model->union_code, 'is_active' => 1])->one();
            if ($union_bank->server_type == 'eipl') {
                $filePath = $union_bank->file_path . date('Y-m-d') . '/';
            } else {
                $filePath = $union_bank->file_path . 'in/';
                $temp_filePath = $filePath . 'eipltemp/';
            }
            $newModel = new TblVspPayment();
            $code_cnt = 0;
            $cnt = 0;
            $query = $newModel->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'tbl_vsp_payment.dcs_code' => $model->dcs_code])
                    ->all();
            foreach ($query as $data) {
                if (!empty($data->dcs_code) && in_array($data->dcs_code, $model->dcs_code)) {
                    $dcsPaymentApp = TblDcsPaymentCycleApplicability::find()->where(['dcs_code' => $data->dcs_code, 'dcs_payment_cycle_code' => $data->dcs_payment_cycle_code])->one();
                    if (!empty($dcsPaymentApp)) {
                        $dcsPaymentApp->data_lock_vsp = 1;
                        $save_model[] = $dcsPaymentApp;
                    }
                    $outstanding = TblVspOutstanding::find()->where(['dcs_code' => $data->dcs_code])->one();
                    if (empty($outstanding)) {
                        $outstanding = new TblVspOutstanding();
                        $outstanding->union_code = $data->union_code;
                        $outstanding->dcs_code = $data->dcs_code;
                    } else {
                        $oshistoryModel = new TblVspOutstandingHistory();
                        Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
                        $save_model[] = $oshistoryModel;
                    }
                    $outstanding->scenario = 'payment';
                    $outstanding->dcs_payment_cycle_code = $data->dcs_payment_cycle_code;
                    $outstanding->hold_amount = $data->hold_amount;
                    $outstanding->due_amount = $data->adjust_amount;
                    $save_model[] = $outstanding;

                    $historyModel = new TblVspPaymentHistory();
                    Yii::$app->operation->history($data, $historyModel, UPDATE);
                    $save_model[] = $historyModel;
                    if ($data->status == 'rejected') {
                        $data->disburse_date = NULL;
                        $data->disburse_amount = 0.00;
                        $data->utr_no = NULL;
                        $data->reference_no = NULL;
                        $data->process_date = NULL;
                        $data->reject_reason = NULL;
                        $data->bank_status = NULL;
                    }
                    $data->status = 'sent';
                    $payment_tr = new TblPaymentTransaction();
                    $payment_tr->attributes = $data->attributes;
                    $payment_tr->total_amount = $data->amount;
                    $payment_tr->total_deduction = $data->deduction;
                    $payment_tr->qty = $data->total_qty;
                    $payment_tr->payment_date = date('Y-m-d H:i:s');
                    $payment_tr->final_amount = round($data->final_pay);
                    $payment_tr->mobile_no = !empty($data->dcsCode && $data->dcsCode->defaultContactDetail) ? $data->dcsCode->defaultContactDetail->mobile_no : NULL;
                    $payment_tr->is_file = 0;
                    $payment_tr->payment_transaction_code = $payment_tr->getMaxCode() + $code_cnt;
                    $payment_tr->code = $data->dcs_code;
                    $payment_tr->name = $data->dcsCode->dcs_name;
                    $payment_tr->type = 'vsp';
                    $save_model[] = $payment_tr;
                    $cnt++;
                    $data->payment_transaction_code = $payment_tr->payment_transaction_code;
                    $code_cnt++;
                    $save_model[] = $data;
                }
            }

            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' VSP of ' . count($query) . ' VSP', 'info']);
            if ($transaction != FALSE && $transaction != 'customRender') {
                $file_data = new TblPaymentTransaction();
                $file_data->type = 'vsp';
                $file_data->dcs_code = $model->dcs_code;
                $file_data->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                $file_data = $file_data->getRecords();
                if (count($file_data) > 0) {
                    $proccess_data = FALSE;
                    if ($union_bank->server_type == 'eipl' && Yii::$app->general->checkDirectory($filePath) && Yii::$app->general->checkDirectory($filePath . 'inprocess') && Yii::$app->general->checkDirectory($filePath . 'inputfile') && Yii::$app->general->checkDirectory($filePath . 'mis')) {
                        $proccess_data = TRUE;
                        $bank_type = 'UBI';
                        $file_folder = $filePath . 'inputfile/';
                    } else if (Yii::$app->general->checkDirectory($filePath) && Yii::$app->general->checkDirectory($temp_filePath)) {
                        $proccess_data = TRUE;
                        $bank_type = 'AXIS';
                        $file_folder = $filePath;
                    }
                    if ($proccess_data) {
                        $text = '';
                        $ubi_text = '';
                        $bank_total_dabit = 0;
                        $bank_total_cnt = 0;

                        foreach ($file_data as $row) {
                            if ($bank_type == 'UBI') {
                                $mobile_no = !empty($row->mobile_no) ? $row->mobile_no : '9999999999';
                                $txtrow = $union_bank->bank_account_no . '|' . //Debit Account No
                                        $row->final_amount . '|' . //Amount
                                        $row->ifsc . '|' . //Memebr IFSC
                                        $row->payment_transaction_code . '|' . //Payment Cycleid/ Payment Referenceid
                                        $row->bank_account_no . '|' . //Member Acc No
                                        $row->name . '|' . //Member Name
                                        $row->code . '|' . //Member code
                                        '' . '|' . //Aadhar No
                                        substr($row->bank_name, 0, 35) . '|' . //Bank Name
                                        $model->union_code . '|' . //Union Code
                                        $row->code . '|' . //DCS Code
                                        $union_bank->bank_account_no . '|' . //Charge Debit A/c
                                        'SMS' . '|' . //Mobile/ Email ID
                                        $mobile_no; //mobile no
                                // $row->memberCode->mobile_no . '|' . //mobile no
                                //'NEFT' . '|' . //Transaction Type
                                //date('d.m.Y'); //Date of Transaction
                                if (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) {
                                    $ubi_text .= $txtrow . PHP_EOL;
                                } else {
                                    $text .= $txtrow . PHP_EOL;
                                }
                                $bank_total_dabit = $bank_total_dabit + $row->final_amount;
                                $bank_total_cnt += 1;
                            } else if ($bank_type == 'AXIS') {
                                $char = (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) ? 'FT' : 'NE';
                                $txtrow = 'P^' . //Identifier
                                        $char . '^' . //Payment Mode (axis to axis - FT/ other-NE )
                                        $union_bank->corporate_code . '^' . //Corporate Code
                                        $row->payment_transaction_code . '^' . //Customer Reference Number
                                        $union_bank->bank_account_no . '^' . //Debit Account No
                                        date('Y-m-d') . '^' . // Value Date
                                        'INR^' . // Transaction Currency
                                        $row->final_amount . '^' . // Transaction Amount
                                        $row->name . '^' . // Beneficiary Name
                                        $row->code . '^' . // Beneficiary Code / Vendor Code
                                        $row->bank_account_no . '^' . // Beneficiary Account Number
                                        '10^' . // Benefciary Account Type
                                        '^' . // Beneficiary Address 1
                                        '^' . // Beneficiary Address 2
                                        '^' . // Beneficiary Address 3
                                        '^' . // Beneficiary City
                                        '^' . // Beneficiary State
                                        '^' . // Beneficiary Pin Code
                                        $row->ifsc . '^' . // Beneficiary IFSC Code
                                        $row->bank_name . '^' . // Beneficiary Bank Name
                                        '^' . // Base Code
                                        '^' . // Cheque Number
                                        '^' . // Cheque Date
                                        '^' . // Payable location
                                        '^' . // Print Location
                                        '^' . // Beneficiary Email address 1
                                        '^' . // Beneficiary Email address 2
                                        '^' . // Beneficiary Mobile Number
                                        '^' . // Corp Batch No
                                        '^' . // Company Code
                                        '^' . // Product Code
                                        '^' . // Extra 1
                                        '^' . // Extra 2
                                        '^' . // Extra 3
                                        '^' . // Extra 4
                                        '^' . // Extra 5
                                        '^' . // PayType
                                        $union_bank->email . '^' . // CORP_EMAIL_ADDR
                                        date('Y-m-d H-i-s') . '^' . // TRANSMISSION DATE
                                        '^'; // User ID,USER DEPARTMENT  
                                $text .= $txtrow . PHP_EOL;
                                $bank_total_dabit = $bank_total_dabit + $row->final_amount;
                                $bank_total_cnt += 1;
                            }
                        }

                        $save_model = [];
                        $NEFT = TRUE;
                        $UBI = TRUE;
                        if ($text != '') {
                            $data_write = FALSE;
                            if ($bank_type == 'AXIS') {
                                $f_name = $union_bank->corporate_code . '_H2H_' . date('dmY') . '_' . $union_bank->union_code . date('His') . ".txt";
                                $fileName = $file_folder . $f_name;
                                $temp_fileName = $temp_filePath . $f_name;
                                $vfile = fopen($temp_fileName, "w") or die("Unable to open file!");
                                if (fwrite($vfile, $text)) {
                                    $data_write = TRUE;
                                    fclose($vfile);
                                } else {
                                    fclose($vfile);
                                    unlink($vfile);
                                }
                            } else {
                                $fileName = $file_folder . 'NEFT_' . date('YmdHis') . ".txt";
                                $vfile = fopen($fileName, "w") or die("Unable to open file!");
                                if (fwrite($vfile, $text)) {
                                    $data_write = TRUE;
                                    fclose($vfile);
                                } else {
                                    fclose($vfile);
                                    unlink($vfile);
                                }
                            }
                            if ($data_write) {
                                $neft_log = new TblBankPaymentLog();
                                $neft_log->union_code = $model->union_code;
                                $neft_log->file_path = $fileName;
                                $neft_log->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                                $neft_log->status = 1; //created
                                $neft_log->payment_date = date('Y-m-d');
                                Yii::$app->operation->defaults($neft_log, INSERT);
                                $neft_log->save();
                            } else {
                                $NEFT = FALSE;
                            }
                        }
                        if ($ubi_text != '') {
                            $fileName = $file_folder . 'UBI_' . date('YmdHis') . ".txt";
                            $ubifile = fopen($fileName, "w") or die("Unable to open file!");
                            if (fwrite($ubifile, $ubi_text)) {
                                $ubi_log = new TblBankPaymentLog();
                                $ubi_log->union_code = $model->union_code;
                                $ubi_log->file_path = $fileName;
                                $ubi_log->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                                $ubi_log->status = 1; //created
                                $ubi_log->payment_date = date('Y-m-d');
                                Yii::$app->operation->defaults($ubi_log, INSERT);
                                $ubi_log->save();
                                fclose($ubifile);
                            } else {
                                fclose($ubifile);
                                unlink($ubifile);
                                $UBI = FALSE;
                            }
                        }

                        if ($NEFT && $UBI) {
                            foreach ($file_data as $data) {
                                $data->is_file = 1;
                                $data->file_datetime = date('Y-m-d H:i:s');
                                if ($bank_type == 'UBI' && (substr($union_bank->ifsc, 0, 4) == substr($data->ifsc, 0, 4))) {
                                    $data->file_id = $ubi_log->log_id;
                                } else {
                                    $data->file_id = $neft_log->log_id;
                                }
                                if ($bank_type == 'AXIS') {
                                    $data->file_name = $f_name;
                                }
                                $save_model[] = $data;
                            }
                            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' VSP of ' . count($query) . ' VSP', 'info']);
                            if ($transaction == 'customRedirect') {
                                if ($bank_type == 'AXIS') {
                                    if (copy($temp_fileName, $fileName)) {
                                        $send_notif = TRUE;
                                        unlink($temp_fileName);
                                    } else {
                                        /* update vsp payment data */
                                        Yii::$app->db->createCommand("update mp set mp.status = 'processed', mp.payment_transaction_code = NULL from tbl_vsp_payment mp
inner join tbl_payment_transaction pt on pt.payment_transaction_code = mp.payment_transaction_code
where pt.file_name = :file_name and mp.status = 'sent'")
                                                ->bindValue(':file_name', $f_name)
                                                ->execute();
                                        /* update vsp payment data */
                                        /* delete payment data */
                                        Yii::$app->db->createCommand("delete from tbl_payment_transaction where file_name = :file_name and type = 'vsp'")
                                                ->bindValue(':file_name', $f_name)
                                                ->execute();
                                        /* delete payment data */
                                    }
                                } else {
                                    $send_notif = TRUE;
                                }
                            } else {
                                $this->deletepayment($model);
                            }
                        } else {
                            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                                'message' => 'An error occurred on the server when processing file']);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                            'message' => 'Error while file processing.']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Bank detail not available.']);
                }
            }
        } catch (\Throwable $ex) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'An error occurred on the server when processing file']);
        }
        if ($send_notif) {
            $this->PaymentNotification($union_bank, $bank_total_dabit, $bank_total_cnt, $fileName);
        }


        return TRUE;
    }

    private function insertPaymentData($model) {
        $from_date = date('Y-m-d', strtotime($model->paymentCycleCode->from_date));
        $to_date = date('Y-m-d', strtotime($model->paymentCycleCode->to_date));
        $bill_head = TblBillHead::find()->where(['union_code' => $model->union_code])->all();
        $fat = 0.00;
        $snf = 0.00;
        $qty = 0;
        $amount = 0;
        $main_amount = 0;
        foreach ($model->dcs_code as $dcs_code) {
            $bill_head_data = [];
            foreach ($bill_head as $data) {
                if ($data->bill_head_name == 'Milk Amount') {
                    $fat = 0.00;
                    $snf = 0.00;
                    $qty = 0;
                    $amount = 0;
                    $main_amount = 0;
                    $adddata = TRUE;
                    $sumdata = FALSE;
                    $milkcoll = TblBmcCollection::find()->select(['fat' => 'round(sum(Round(qty * fat/100,4)),2) ', 'snf' => 'round(sum(Round(qty * snf/100,4)),2) ', 'amount' => 'SUM(amount)', 'qty' => 'SUM(qty)'])->where(['dcs_code' => $dcs_code])
                                    ->andWhere(['>=', 'date_time_of_collection', $from_date])
                                    ->andWhere(['<=', 'date_time_of_collection', $to_date])
                                    ->groupBy('dcs_code')->asArray()->one();
                    if (!empty($milkcoll)) {
                        $amount = $milkcoll['amount'];
                        $qty = $milkcoll['qty'];
                        $fat = $milkcoll['fat'];
                        $snf = $milkcoll['snf'];
                    }
                    $main_amount = $amount;
                } else if ($data->bill_head_name == 'Member Payable') {
                    $adddata = TRUE;
                    $sumdata = TRUE;
                    $milkcoll = TblMilkCollection::find()->select(['amount' => 'SUM(amount)', 'qty' => 'SUM(qty)'])->where(['dcs_code' => $dcs_code])
                                    ->andWhere(['>=', 'date_time_of_collection', $from_date])
                                    ->andWhere(['<=', 'date_time_of_collection', $to_date])
                                    ->groupBy('dcs_code')->asArray()->one();
                    $amount = !empty($milkcoll) ? $milkcoll['amount'] : 0;
                } else {
                    $adddata = TRUE;
                    $sumdata = TRUE;
                    $milkcoll = TblBillHeadDetail::find()->select(['amount' => 'SUM(CAST(amount as decimal(18,2)))'])
                                    ->where(['dcs_code' => $dcs_code, 'bill_head_code' => $data->bill_head_code])
                                    ->andWhere(['payment_cycle_code' => $model->dcs_payment_cycle_code])
                                    ->groupBy('dcs_code')->asArray()->one();
                    $amount = !empty($milkcoll) ? $milkcoll['amount'] : 0;
                    if (empty($milkcoll)) {
                        $adddata = FALSE;
                    }
                }
                if ($adddata) {
                    $bill_head_data[] = [
                        'amount' => $amount,
                        'type' => $data->bill_head_type,
                        'code' => $data->bill_head_code,
                        'sumdata' => $sumdata
                    ];
                }
            }
            $payment = new TblVspPayment();
            $payment->vsp_payment_code = Yii::$app->general->getCodeAutoIncrement($payment);
            $payment->union_code = $model->union_code;
            $payment->dcs_code = $dcs_code;
            $payment->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
            $payment->total_qty = $qty;
            $payment->kg_fat = $fat;
            $payment->kg_snf = $snf;
            $payment->amount = $main_amount;
            $payment->payment_date = date('Y-m-d H:i:s');
            $payment->status = 'processed';
            if (isset($payment->dcsCode->defaultBankDetail)) {
                $payment->bank_code = $payment->dcsCode->defaultBankDetail->bank_code;
                $payment->branch_code = $payment->dcsCode->defaultBankDetail->branch_code;
                $payment->bank_name = !empty($payment->dcsCode->defaultBankDetail->bankCode) ? $payment->dcsCode->defaultBankDetail->bankCode->bank_name : NULL;
                $payment->branch_name = !empty($payment->dcsCode->defaultBankDetail->branchCode) ? $payment->dcsCode->defaultBankDetail->branchCode->branch_name : NULL;
                $payment->ifsc = $payment->dcsCode->defaultBankDetail->ifsc;
                $payment->bank_account_no = $payment->dcsCode->defaultBankDetail->bank_account_no;
                $payment->is_verified = 1;
            }
            $tot_qty = 0;
            $tot_addition = 0;
            $tot_deduction = 0;
            $tot_net = 0;
            $tot_final = 0;
            $saveArray = [];
            foreach ($bill_head_data as $inshead) {
                $trn = new TblVspPaymentTransaction();
                $trn->vsp_payment_code = $payment->vsp_payment_code;
                $trn->amount = $inshead['amount'];
                $trn->bill_head_code = $inshead['code'];
                $trn->bill_head_type = $inshead['type'];
                $saveArray[] = $trn;
                if ($inshead['sumdata']) {
                    if ($trn->bill_head_type == 0) {
                        $tot_addition += $trn->amount;
                    } else {
                        $tot_deduction += $trn->amount;
                    }
                }
            }
            $payment->final_pay = ($payment->amount + $tot_addition) - $tot_deduction;
            $payment->net_payable = $payment->final_pay;
            $payment->addition = $tot_addition;
            $payment->deduction = $tot_deduction;
            $saveArray[] = $payment;
            $transaction = $this->generalModel->saveTransaction($saveArray, ['Payment Processed Succesfully.', 'info']);
        }
    }

    public function deletepayment($model) {
        /* update vsp payment data */
        Yii::$app->db->createCommand("update mp set mp.status = 'processed', mp.payment_transaction_code = NULL from tbl_vsp_payment mp
inner join tbl_payment_transaction pt on pt.payment_transaction_code = mp.payment_transaction_code
where pt.dcs_code IN (:dcs_code) and pt.dcs_payment_cycle_code = :dcs_payment_cycle_code and pt.type = 'vsp' and mp.status = 'sent' and pt.is_file = 0")
                ->bindValue(':dcs_code', is_array($model->dcs_code) ? implode(',', $model->dcs_code) : $model->dcs_code)
                ->bindValue(':dcs_payment_cycle_code', $model->dcs_payment_cycle_code)
                ->execute();
        /* update vsp payment data */
        /* delete payment data */
        Yii::$app->db->createCommand("delete from tbl_payment_transaction
where dcs_code IN (:dcs_code) and dcs_payment_cycle_code = :dcs_payment_cycle_code and type = 'vsp' and is_file = 0")
                ->bindValue(':dcs_code', is_array($model->dcs_code) ? implode(',', $model->dcs_code) : $model->dcs_code)
                ->bindValue(':dcs_payment_cycle_code', $model->dcs_payment_cycle_code)
                ->execute();
        /* delete payment data */
    }

    public function actionCheckPaymentProcessed() {
        $model = new TblVspPayment();
        $model->attributes = Yii::$app->request->post();
        $count = $model->getRecords()->count();
        $msg = '';
        if ($count > 0) {
            $msg = Yii::t('app', 'Payment already processed for selected input.');
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['message' => $msg]);
    }

    public function setVal($value) {
        if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
            echo "<td>" . $value . "</td>";
        } else {
            echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
        }
    }

    public function actionAddRecovery() {
        $model = $this->findModel(Yii::$app->request->get()['code']);
        if (Yii::$app->request->post()) {
            $process = FALSE;
            $postData = Yii::$app->request->post()['TblVspPayment'];
            $total_amount = (float) abs($model->net_payable);
            $total_recovery = (float) array_sum(array_column($postData, 'new_recovery'));
            if ($total_recovery <= $total_amount) {
                $saveModel = [];
                $deleteModel = [];
                foreach ($postData as $data) {
                    $is_delete = FALSE;
                    $record = TblVspPayment::findOne($data['vsp_payment_code']);
                    $rec_model = TblVspPaymentRecovery::find()
                            ->where(['payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type,
                                'from_customer_code' => $record->customer_code, 'for_customer_code' => $model->customer_code])
                            ->one();
                    $old_rec = 0.00;
                    $new_rec = !empty($data['new_recovery']) ? $data['new_recovery'] : 0.00;
                    if (!empty($rec_model)) {
                        $old_rec = $rec_model->recovery_amount;
                        $rec_model->recovery_amount = $new_rec;
                        $is_delete = ($new_rec == 0.00) ? TRUE : FALSE;
                    } else if (!empty($new_rec) && $new_rec != 0.00) {
                        $rec_model = new TblVspPaymentRecovery();
                        $rec_model->attributes = $model->attributes;
                        $rec_model->for_customer_code = $model->customer_code;
                        $rec_model->from_customer_code = $record->customer_code;
                        $rec_model->recovery_amount = $new_rec;
                        $rec_model->created_at = $rec_model->created_by = $model->updated_at = $rec_model->updated_by = $rec_model->originating_org_code = $rec_model->originating_org_type = $rec_model->originating_type = NULL;
                    }
                    if ($old_rec != $new_rec) {
                        $process = TRUE;
                        $record->final_pay = $record->final_pay + $old_rec - $new_rec;
                        $record->recovery = $record->recovery - $old_rec + $new_rec;
                        $saveModel[] = $record;
                        if ($is_delete) {
                            $deleteModel[] = $rec_model;
                        } else {
                            $saveModel[] = $rec_model;
                        }
                    }
                }
                if ($process) {
                    $model->final_pay = $model->final_pay - $model->adjust_recovery + $total_recovery;
                    $model->adjust_recovery = $total_recovery;
                    $saveModel[] = $model;
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Adjust Recovery', 'create']);
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'msg' => $msg];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'msg' => $msg];
                    }
                } else {
                    $msg = Yii::t('app', 'No Changes found in data.');
                    $record = ['status' => 'error', 'msg' => $msg];
                }
            } else {
                $msg = Yii::t('app', 'Sum Of New Recovery must not be grater than Total Amount.');
                $record = ['status' => 'error', 'msg' => $msg];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
        $recoveryData = $model->getRecoveryRecords();
        return $this->renderAjax('add-recovery', [
                    'model' => $model,
                    'recoveryData' => $recoveryData]);
    }

}
