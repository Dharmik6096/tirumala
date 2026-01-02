<?php

namespace app\modules\payment\controllers;

use app\modules\organisation\models\TblCustomerMaster;
use app\modules\payment\models\TblVendorPaymentHoldRelease;
use app\modules\payment\models\TblVendorPaymentHoldReleaseHistory;
use Yii;
use app\modules\payment\models\TblVendorPaymentHoldReleaseSummary;
use app\modules\payment\models\TblVendorPaymentHoldReleaseTransaction;
use app\modules\payment\models\TblVendorPaymentHoldReleaseTransactionSearch;
use app\modules\payment\models\TblVspPayment;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\payment\models\TblVspOutstanding;
use app\modules\payment\models\TblVspOutstandingHistory;
use PHPExcel;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;

class TblVendorPaymentHoldReleaseController extends \app\controllers\ChildController {

    public $freeAccessActions = ['hold-release-payment-cycle', 'process-hold-release'];
   
    public function actionCreate() {
        $model = new TblVendorPaymentHoldReleaseSummary();
        $model->scenario = 'processpayment';
        if ($model->load(Yii::$app->request->post())) {
            $result = 'success';
            if ($model->validate()) {
                $validate = $model->ValidateDate('from_datetime', NULL);
                $queryParam = [];
                $queryParam[] = 'process-hold-release';
                $queryParamRegenerate = [];
                $queryParam['TblVendorPaymentHoldReleaseSummary'] = [
                    'from_datetime' => $model->from_datetime, 
                    'to_datetime' => $model->to_datetime, 
                    'customer_type' => $model->customer_type,
                    'plant_code' => $model->plant_code, 
                    'mcc_plant_code' => $model->mcc_plant_code, 
                    'bmc_code' => $model->bmc_code,
                    'union_code' => $model->union_code];
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

    public function actionProcessHoldRelease($reGenerate = 0) {
        $multiple_bmc = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') == '1' ? TRUE : FALSE;

        if (Yii::$app->request->get()) {
            $model = new TblVendorPaymentHoldReleaseSummary();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $bmc_array = [];
                $bmc_code = $model->bmc_code;

                $customer_array = [];
                $customer = $model->customer_type;

                if (is_array($model->bmc_code)) {
                    $bmc_array = $model->bmc_code;
                } else {
                    $bmc_array[] = $model->bmc_code;
                }

                if (is_array($model->customer_type)) {
                    $customer_array = $model->customer_type;
                } else {
                    $customer_array[0] = $model->customer_type;
                }

                $model->from_datetime = date('Y-m-d', strtotime($model->from_datetime)) . ' ' . \Yii::$app->general->getshift(1);
                $model->to_datetime = date('Y-m-d', strtotime($model->to_datetime)) . ' ' . \Yii::$app->general->getshift(2);

                foreach ($bmc_array as $bmc) {
                    foreach ($customer_array as $type) {
                        $model->customer_type = $type;
                        $model->bmc_code = $bmc;
                        $this->getHoldReleaseSpData($model);
                    }
                }
                $model->bmc_code = $bmc_code;
                $model->customer_type = $customer;
            }
            return $this->redirect(['payment-adjust', 'TblVendorPaymentHoldRelease' => [
                'from_datetime'  => $model->from_datetime,
                'to_datetime'    => $model->to_datetime,
                'bmc_code'       => $model->bmc_code,
                'mcc_plant_code' => $model->mcc_plant_code,
                'plant_code'     => $model->plant_code,
                'union_code'     => $model->union_code,
                'customer_type'  => $model->customer_type,
                'multiple_bmc'   => $multiple_bmc
            ]]);
        }
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblVendorPaymentHoldRelease();
        $model->load(Yii::$app->request->get());
        if (Yii::$app->request->post()) {
            $bmc_array = [];
            $postData = Yii::$app->request->post();
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'processed';
            $adjust_id = Yii::$app->request->post('TblVendorPaymentHoldRelease')['vendor_payment_hold_release_code'];
            $adjust_amt = Yii::$app->request->post('TblVendorPaymentHoldRelease')['adjust_amount'];
            $adjust_remark = Yii::$app->request->post('TblVendorPaymentHoldRelease')['adjust_remark'];
            $hold_amt = Yii::$app->request->post('TblVendorPaymentHoldRelease')['hold_amount'];
            $save_model = [];
            foreach ($adjust_id as $key => $value) {
                $data = TblVendorPaymentHoldRelease::findOne($adjust_id[$key]);
                $oldData = $data->oldAttributes;
                $historyModel = new TblVendorPaymentHoldReleaseHistory();
                Yii::$app->operation->history($data, $historyModel, UPDATE);
                // if (($adjust_amt[$key] != 0 && $adjust_amt[$key] != '') || ($hold_amt[$key] != 0 && $hold_amt[$key] != '')) {
                if ($oldData['hold_amount'] != $hold_amt[$key] || $oldData['adjust_amount'] != $adjust_amt[$key]) {
                    $updateData = false;
                    $data->adjust_amount = $adjust_amt[$key];
                    $data->adjust_remark = $adjust_remark[$key];
                    $data->hold_amount = $hold_amt[$key];
                    $data->final_pay = (float) $data->net_payable + (float) $adjust_amt[$key] - (float) $hold_amt[$key];
                    $data->status = (empty($processFlag)) ? 'processed' : $processFlag;
                    if (!empty($oldData) && ($oldData['status'] != $data->status || $oldData['hold_amount'] != $data->hold_amount || $oldData['adjust_amount'] != $data->adjust_amount || $oldData['adjust_remark'] != $data->adjust_remark)) {
                        $updateData = true;
                    }
                    if ($updateData) {
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                    }
                } else {
                    $data->status = (empty($processFlag)) ? 'processed' : $processFlag;
                    if (!empty($oldData) && ($oldData['status'] != $data->status)) {
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                    }
                }
                $model->from_datetime = $data->from_datetime;
                $model->to_datetime = $data->to_datetime;
                $bmc_array[$data->bmc_code] = $data->bmc_code;
                $model->union_code = $data->union_code;
                $model->customer_type = $data->customer_type;
            }
            $PaymentApp = TblVendorPaymentHoldReleaseSummary::find()
                    ->where(['from_datetime' => $model->from_datetime,
                        'to_datetime' => $model->to_datetime,
                        'bmc_code' => $bmc_array,
                        'union_code' => $model->union_code])
                    ->all();
            foreach ($PaymentApp as $dataApp) {
                if ($dataApp->status != $processFlag) {
                    $dataApp->status = $processFlag;
                    $save_model[] = $dataApp;
                }
            }
            $customer_type = Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment of ' . $customer_type . ' ' . $processFlag . ' succesfully', 'info']);

            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            $msg = '';
            $url = Url::to(['create']);
            if ($transaction == 'customRedirect') {
                $result = 'success';
            } else {
                $result = 'error';
                $msg = !empty($msg['message']) ? $msg['message'] : '';
            }
            return ['status' => $result, 'url' => $url, 'msg' => $msg];
        }

        $query = $model->getRecords();
        $title = 'Vendor Payment Hold Release Process : Step 2';

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

    private function getHoldReleaseSpData($model) {
        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $data = [];
        $data['union_code'] = $model->union_code;
        $data['plant_code'] = $model->plant_code;
        $data['mcc_plant_code'] = $model->mcc_plant_code;
        $data['bmc_code'] = $model->bmc_code;
        $data['customer_type'] = $model->customer_type;
        $data['from_datetime'] = $model;
        $data['to_datetime'] = $model;
        $data['user_code'] = $user;
        return Yii::$app->ClientPaymentConfig->processPayment('hold_release_payment', $data);
    }

    public function actionBillHead() {
        $searchModel = new TblVendorPaymentHoldReleaseTransactionSearch();
        $searchModel->vendor_payment_hold_release_code = Yii::$app->request->get()['code'];
        $model = $this->findModel($searchModel->vendor_payment_hold_release_code);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderAjax('bill-head-view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    public function actionPaymentDisburse() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblVendorPaymentHoldRelease();
        $model->load(Yii::$app->request->get());
        $query = $model->find();
        if (!$model->validate()) {
            $query = $query->where('0=1');
        } else {
            if (!empty($model->payment_cycle_code)) {
                $payment_cycle_code = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $payment_cycle_code[0];
                $model->to_datetime = $payment_cycle_code[1];
            }
            $query = $query->where([
                'from_datetime' => $model->from_datetime,
                'to_datetime' => $model->to_datetime,
                'bmc_code' => $model->bmc_code,
                'union_code' => $model->union_code,
                'status' => ['locked', 'rejected']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Hold Release Payment Disburse : Step 1'
        ]);
    }

    public function actionConfirmPayment() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        if (Yii::$app->request->post()) {
            $model = new TblVendorPaymentHoldRelease();
            $model->load(Yii::$app->request->post());
            $model->customer_type = 'DCS';
            if (!empty($model->payment_cycle_code)) {
                $payment_cycle_code = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $payment_cycle_code[0];
                $model->to_datetime = $payment_cycle_code[1];
                if (Yii::$app->request->post('flag') == 'vendor') {
                    $query = $model->find()->where([
                        'from_datetime' => $model->from_datetime,
                        'to_datetime' => $model->to_datetime,
                        'customer_type' => $model->customer_type,
                        'bmc_code' => $model->bmc_code,
                        'union_code' => $model->union_code,
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
                        'message' => 'Out of (<b>' . $tot_cnt . '</b>) Hold Release Payment of (<b>' . $pay_cnt . '</b>)  Vendor will be only done.<br/>Total Payable :: <b>' . $pay_amount . '</b>']);

                    return $this->render('confirm-payment', [
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
        $model = new TblVendorPaymentHoldRelease();
        $model->load(Yii::$app->request->post());
        $customer = new TblCustomerMaster();
        if (empty($model->customer_type) && !empty($model->bmc_code)) {
            $data = $customer->customerType($model->bmc_code);
            $model->customer_type = array_keys($data);
        }
        $msg = $this->LockBilling($model);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        $paymentcycle = $model->paymentCycleCode;
        $from_date = Yii::$app->controls->view_date($paymentcycle->from_date);
        $to_date = Yii::$app->controls->view_date($paymentcycle->to_date);
        $msg .= '(' . $from_date . ' to ' . $to_date . ') - hold release payment disbursed successfully';
        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
            'message' => \Yii::t('app', '' . $msg)]);
        return $this->redirect(['tbl-vsp-payment/index']);
    }

    protected function LockBilling($model) {
        $bmc_array = [];
        $msg = '';
        $bmc_array[] = $model->bmc_code;
        $customer_array[] = $model->customer_type;
        if (is_array($model->bmc_code)) {
            $bmc_array = $model->bmc_code;
        }
        if (is_array($model->customer_type)) {
            $customer_array = $model->customer_type;
        }
        foreach ($bmc_array as $bmc_code) {
            $model->bmc_code = $bmc_code;
            $bmc = $model->bmcCode;
            foreach ($customer_array as $customerType) {
                $param = [];
                $param['customer_type'] = $customerType;
                $param['bmc_code'] = $bmc_code;
                $param['applicable_for'] = 'BMC';
                $param['payment_cycle_code'] = $model->payment_cycle_code;
                $param['user_code'] = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                // $param['is_without_release'] = $model->payment_release_type;
                $param['union_bank_payment_code'] = !empty($model->union_bank_payment_code) ? $model->union_bank_payment_code : null;
                // Yii::$app->ClientPaymentConfig->processPayment('vsp_payment_disburse', $param);
                $model->customer_type = $customerType;
                $msg .= $model->customerType->customer_desc . ' - ' . $bmc->ref_code . ' ' . $bmc->bmc_name . "<br>";
            }
        }

        return $msg;
    }

    protected function exportCSV($model) {
        $newModel = new TblVendorPaymentHoldRelease();
        $query = $newModel->find()
                ->where(['tbl_vendor_payment_hold_release.from_datetime' => $model->from_datetime,
                    'tbl_vendor_payment_hold_release.to_datetime' => $model->to_datetime,
                    'tbl_vendor_payment_hold_release.bmc_code' => $model->bmc_code,
                    'tbl_vendor_payment_hold_release.union_code' => $model->union_code,
                    'tbl_vendor_payment_hold_release.status' => ['locked', 'rejected']])
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
        }
        $fileName = "vendor_hold_release_payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionHoldReleasePaymentCycle() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) &&  !empty($parents[1])) {
                $bmc_array = !empty($parents[1]) ? $parents[1] : [];
                $model = new TblVendorPaymentHoldReleaseSummary();
                $data = $model->HoldReleasePaymentCycle($parents[0], $bmc_array);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Finds the TblVspPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVendorPaymentHoldRelease the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVendorPaymentHoldRelease::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
