<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMemberPayment;
use app\modules\payment\models\TblMemberPaymentSearch;
use app\modules\payment\models\TblTmpMemberPayment;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\modules\payment\models\TblDcsPayment;
use yii\web\Response;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use PHPExcel;
use app\modules\payment\models\TblDcsPaymentHistory;
use app\modules\payment\models\TblMemberPaymentHistory;
use app\modules\payment\models\TblUnionBankPayment;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblPaymentOtpVerification;
use app\modules\organisation\models\TblDcs;
use app\modules\verification\models\TblVerification;
use app\modules\payment\models\TblPaymentTransaction;
use yii\data\ActiveDataProvider;

/**
 * TblMemberPaymentController implements the CRUD actions for TblMemberPayment model.
 */
class TblMemberPaymentController extends \app\controllers\ChildController {

    /**
     * Creates a new TblMemberPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMemberPayment();
        $paymentcycleModel = new TblDcsPaymentCycleApplicability();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['list-payment', 'TblMemberPayment' => ['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'dcs_code' => $model->dcs_code, 'union_code' => $model->union_code]]);
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionListPayment() {
        if (Yii::$app->request->get()) {
            $model = new TblMemberPayment();
            $model->load(Yii::$app->request->get());
            $query = $this->getDcsSpData($model);
            $dataProvider = new ArrayDataProvider([
                'allModels' => $query,
                'sort' => [
                    'defaultOrder' => ['dcs_name' => SORT_ASC],
                    'attributes' => [
                        'dcs_name',
                        'total_amount',
                        'total_deduction',
                        'final_amount',
                    ],
                ],
            ]);
            return $this->render('confirm-society', [
                        'model' => $model,
                        'dataProvider' => $dataProvider
            ]);
        }
    }

    private function getSpData($model) {
        $payment_cycle[0] = date('Y-m-d', strtotime($model->paymentCycleCode->from_date));
        $payment_cycle[1] = date('Y-m-d', strtotime($model->paymentCycleCode->to_date));
        $payment_cycle_code = $model->dcs_payment_cycle_code;
        $dcs_list = implode(',', $model->dcs_code);
        $result = \Yii::$app->db->createCommand("{CALL sp_payment_processing_data(:from_date,:to_date,:dcs_list,:payment_cycle_code)}")
                ->bindValue(':from_date', $payment_cycle[0])
                ->bindValue(':to_date', $payment_cycle[1])
                ->bindValue(':dcs_list', $dcs_list)
                ->bindValue(':payment_cycle_code', $payment_cycle_code);
        $query = $result->queryAll();
        return $query;
    }

    private function getDcsSpData($model) {
        $payment_cycle[0] = date('Y-m-d', strtotime($model->paymentCycleCode->from_date));
        $payment_cycle[1] = date('Y-m-d', strtotime($model->paymentCycleCode->to_date));
        $payment_cycle_code = $model->dcs_payment_cycle_code;
        $dcs_list = implode(',', $model->dcs_code);
        $result = \Yii::$app->db->createCommand("{CALL sp_dcs_payment_processing_data(:from_date,:to_date,:dcs_list,:payment_cycle_code)}")
                ->bindValue(':from_date', $payment_cycle[0])
                ->bindValue(':to_date', $payment_cycle[1])
                ->bindValue(':dcs_list', $dcs_list)
                ->bindValue(':payment_cycle_code', $payment_cycle_code);
        $query = $result->queryAll();
        return $query;
    }

    public function actionListSociety() {
        $paymentcycleModel = new TblDcsPaymentCycleApplicability();
        $society_list = $paymentcycleModel->societyList(Yii::$app->request->post('payment_cycle'));
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $society_list]);
    }

    public function actionConfirmSociety() {
        if (Yii::$app->request->post()) {
            $model = new TblMemberPayment();
            $model->load(Yii::$app->request->post());
            $tmp_data = $this->getSpData($model);
            $dcs_data = $this->getDcsSpData($model);
            $save_model = [];
            $delete_model = [];
            $dcs_ai = 0;
            $member_ai = 0;
            foreach ($dcs_data as $d) {
                $dcsPayment = new TblDcsPayment();
                $dcsPayment->attributes = $d;
                $dcsPayment->member_count = array_count_values(array_column($tmp_data, 'dcs_code'))[$d['dcs_code']];
                $dcsPayment->union_code = $dcsPayment->dcsCode->union_code;
                $dcsPayment->dcs_payment_code = str_pad($dcsPayment->getCode() + $dcs_ai, 11, '0', STR_PAD_LEFT);
                $dcsPayment->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                $dcsPayment->dcs_payment_cycle_applicabilty_code = $d['payment_cycle_applicabilty_code'];
                $dcsPayment->payment_date = date('Y-m-d H:i:s');
                $dcsPayment->status = 'processed';
                $save_model[] = $dcsPayment;
                $dcs_ai++;
                $deleteRecord = TblDcsPayment::find()
                                ->where(['status' => ['processed'], 'union_code' => $model->union_code, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'dcs_code' => $d['dcs_code']])->one();
                if (!empty($deleteRecord)) {
                    $historyModel = new TblDcsPaymentHistory();
                    Yii::$app->operation->history($deleteRecord, $historyModel, DELETE);
                    $delete_model[] = $historyModel;
                    $delete_model[] = $deleteRecord;
                    $historyModel->save(FALSE);
                    $deleteRecord->delete(FALSE);
                }
            }
            foreach ($tmp_data as $data) {
                $memberPayment = new TblMemberPayment();
                $memberPayment->attributes = $data;
                $memberPayment->union_code = $memberPayment->dcsCode->union_code;
                $memberPayment->member_payment_code = str_pad($memberPayment->getCode() + $member_ai, 11, '0', STR_PAD_LEFT);
                $memberPayment->dcs_payment_cycle_code = $model->dcs_payment_cycle_code;
                $memberPayment->dcs_payment_cycle_applicabilty_code = $data['payment_cycle_applicabilty_code'];
                $memberPayment->payment_date = date('Y-m-d H:i:s');
                $memberPayment->status = 'processed';
                $memberPayment->bank_code = $memberPayment->memberCode->bank_code;
                $memberPayment->branch_code = $memberPayment->memberCode->branch_code;
                $memberPayment->bank_name = !empty($memberPayment->memberCode->bankCode) ? $memberPayment->memberCode->bankCode->bank_name : NULL;
                $memberPayment->branch_name = !empty($memberPayment->memberCode->branchCode) ? $memberPayment->memberCode->branchCode->branch_name : NULL;
                $memberPayment->ifsc = $memberPayment->memberCode->ifsc;
                $memberPayment->bank_account_no = $memberPayment->memberCode->bank_account_no;
                $memberPayment->is_verified = isset($memberPayment->verifiedBank) ? 1 : (isset($memberPayment->rejectedBank) ? 2 : 0);
                $save_model[] = $memberPayment;
                $member_ai++;
                $deleteRecord = TblMemberPayment::find()
                                ->where(['status' => ['processed'], 'union_code' => $model->union_code, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'dcs_code' => $data['dcs_code']])->one();
                if (!empty($deleteRecord)) {
                    $historyModel = new TblMemberPaymentHistory();
                    Yii::$app->operation->history($deleteRecord, $historyModel, DELETE);
                    $delete_model[] = $historyModel;
                    $delete_model[] = $deleteRecord;
                    $historyModel->save(FALSE);
                    $deleteRecord->delete(FALSE);
                }
            }
            //     $delete_transaction = $this->generalModel->appTransaction($delete_model, ['Payment', 'info']);
            //     if ($delete_transaction !== FALSE && $delete_transaction != 'customRender') {
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Cycle Successfully Generated for ' . count(array_unique(array_column($tmp_data, 'dcs_code'))) . ' Society', 'info']);
            if ($transaction !== FALSE && $transaction != 'customRender') {
                return $this->redirect(['payment-adjust', 'union_code' => $model->union_code, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'dcs_code' => $model->dcs_code]);
            }
            //   }
        }
        return $this->redirect(\yii\helpers\Url::previous());
    }

    public function actionExportPaymentList() {
        $model = new TblMemberPayment();
        $dcsModel = new TblDcsPayment();
        $model->load(Yii::$app->request->get());
        $query = [];
        if (!empty($model->dcs_payment_cycle_code)) {
            $query = $dcsModel->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected']])
                            ->joinWith(['dcsCode'])
                            ->asArray()->all();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false
        ]);
        return $this->render('index', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Farmer Payment Disburse : Step 1'
        ]);
    }

    public function actionPaymentMembers($cycle, $dcs_code) {
        $model = new TblMemberPayment();
        $model->dcs_payment_cycle_code = $cycle;
        $model->dcs_code = $dcs_code;
        if (!empty($model->dcs_payment_cycle_code)) {
            $newModel = new TblMemberPayment();
            $query = $newModel->find()->select(['member_payment_code', 'tbl_member_payment.dcs_code', 'dcs_name', 'tbl_member_payment.member_code', 'member_name', 'total_amount', 'total_deduction', 'final_amount', 'adjust_amount', 'adjust_remark'])->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'tbl_member_payment.dcs_code' => $dcs_code])
                            ->groupBy(['member_payment_code', 'tbl_member_payment.dcs_code', 'tbl_member_payment.member_code', 'dcs_name', 'member_name', 'total_amount', 'total_deduction', 'final_amount', 'adjust_amount', 'adjust_remark'])
                            ->joinWith(['dcsCode', 'memberCode'])
                            ->asArray()->all();
        } else {
            $query = $model->find()->where('0=1')->asArray()->all();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
        ]);
        return $this->render('member-index', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMemberPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function exportCSV($model) {
        $newModel = new TblMemberPayment();
        $query = $newModel->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'tbl_member_payment.dcs_code' => $_REQUEST['selection']])
                ->joinWith(['dcsCode', 'memberCode'])->joinWith(['memberCode.bankCode', 'memberCode.branchCode'])
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
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Society Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Society Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Member Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Member Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Account No');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Bank');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Branch');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'IFSC');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Total Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Adjsut Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'Final Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'Adjsut Remarks');
        foreach ($query as $row) {
            if ($row->final_amount > 0) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->dcs_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->dcsCode->dcs_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->member_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->memberCode->member_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, '="' . $row->bank_account_no . '"');
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->bank_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->branch_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->ifsc);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->total_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->adjust_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row->final_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row->adjust_remark);
            }
        }
        $fileName = "payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    protected function exportTxt($flag, $model) {
        $union_bank = TblUnionBankPayment::find()->where(['union_code' => $model->union_code])->one();
        if ($union_bank->server_type == 'eipl') {
            $filePath = $union_bank->file_path . date('Y-m-d') . '/';
        } else {
            $filePath = $union_bank->file_path . 'in/';
        }
        $newModel = new TblMemberPayment();
        $query_vsp = [];
        $verified_vsp = [];
        $verified_vsp_refno = [];
        $farmer_cnt = [];
        $cnt = 0;
        $society_cnt = 0;
        $query = $newModel->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'tbl_member_payment.dcs_code' => $model->dcs_code])
                ->all();
        if (Yii::$app->session->get('payment_type') == 'member-vsp') {
            if (Yii::$app->session->get('makerChecker') == 1) {
                $query_vsp = $newModel->find()
                        ->select(['dcs_payment_cycle_applicabilty_code', 'dcs_code', 'count(dcs_code) As member_code', 'sum(total_amount) As total_amount', 'sum(total_deduction) As total_deduction',
                            'sum(final_amount) As final_amount', 'sum(final_amount) As disburse_amount'])
                        ->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed'], 'tbl_member_payment.dcs_code' => $model->dcs_code])
                        ->andWhere(['is_verified' => [0]])
                        ->groupBy('dcs_code,dcs_payment_cycle_applicabilty_code')
                        ->all();
            } else {
                $query_vsp = $newModel->find()
                        ->select(['dcs_payment_cycle_applicabilty_code', 'dcs_code', 'count(dcs_code) As member_code', 'sum(total_amount) As total_amount', 'sum(total_deduction) As total_deduction',
                            'sum(final_amount) As final_amount', 'sum(final_amount ) As disburse_amount'])
                        ->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed'], 'tbl_member_payment.dcs_code' => $model->dcs_code])
                        // ->andWhere(['or', ['=', 'bank_code', ''], ['is', 'bank_code', NULL]])
                        ->andWhere(['or', "ifsc is null or ifsc=''", "bank_account_no is null or bank_account_no=''"])
                        ->groupBy('dcs_code,dcs_payment_cycle_applicabilty_code')
                        ->all();
            }
        }
        foreach ($model->dcs_code as $dcs) {
            $dcsPaymentApp = TblDcsPaymentCycleApplicability::find()->where(['dcs_code' => $dcs, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code])->one();
            $dcsPaymentApp->data_lock = 1;
            $save_model[] = $dcsPaymentApp;
        }
        $dcs_ai = 0;
        foreach ($query_vsp as $data) {
            if (!empty($data->dcsCode->defaultBankDetail)) {
                $data->is_verified = 0;
                if (Yii::$app->session->get('makerChecker') == 1) {
                    $verified_dcs = new TblVerification();
                    $verified_dcs->module_field = 'bank_account_no';
                    $verified_dcs->module_id = $data->dcsCode->defaultBankDetail->detail_code;
                    $verified_dcs->module_name = 'TblBankDetails';
                    if (!empty($verified_dcs->getVerifiedBank())) {
                        $verified_vsp[] = $data->dcs_code;
                        $data->is_verified = 1;
                        $update = TRUE;
                    } else {
                        $update = FALSE;
                    }
                } else {
                    $update = TRUE;
                    $verified_vsp[] = $data->dcs_code;
                }
                if ($update) {
                    $dcsPayment = TblDcsPayment::find()->where(['union_code' => $model->union_code, 'dcs_code' => $data->dcs_code, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code])->one();
                    $dcsPayment->status = 'sent';
                    $save_model[] = $dcsPayment;
                    $society_cnt++;
                    if ($data->final_amount > 0) {
                        $payment_tr = new TblPaymentTransaction();
                        // $payment_tr->attributes = $dcsPayment->attributes;
                        //  $payment_tr->attributes = $data->attributes;
                        $payment_tr->setAttributes($dcsPayment->attributes);
                        $payment_tr->setAttributes($data->oldattributes);
                        $payment_tr->member_count = $data->member_code;
                        $payment_tr->setAttributes($data->dcsCode->defaultBankDetail->attributes);
                        $payment_tr->bank_name = $data->dcsCode->defaultBankDetail->bankCode->bank_name;
                        $payment_tr->branch_name = $data->dcsCode->defaultBankDetail->branchCode->branch_name;
                        $payment_tr->type = 'dcs';
                        $payment_tr->union_code = $model->union_code;
                        $payment_tr->code = $data->dcs_code;
                        $payment_tr->payment_transaction_code = str_pad($payment_tr->getCode() + $dcs_ai, 11, '0', STR_PAD_LEFT);
                        $payment_tr->payment_date = date('Y-m-d H:i:s');
                        $payment_tr->is_file = 0;
                        $payment_tr->name = $data->dcsCode->dcs_name;
                        $payment_tr->is_verified = $data->is_verified;
                        $payment_tr->mobile_no = isset($data->dcsCode->defaultContactDetail) ? $data->dcsCode->defaultContactDetail->mobile_no : '';
                        $payment_tr->transfer_mode = '1';
                        $payment_tr->disburse_amount = NULL;
                        $save_model[] = $payment_tr;
                        $dcs_ai++;
                        $verified_vsp_refno[$data->dcs_code] = $payment_tr->payment_transaction_code;
                    }
                }
            }
        }
        $code_cnt = 0;
        foreach ($query as $data) {
//            $update = FALSE;
//            $insert = FALSE;
//            if ($data->ifsc != '' && $data->ifsc != NULL && $data->bank_account_no != '' && $data->bank_account_no != NULL) {
//                if (Yii::$app->session->get('makerChecker') == 1) {
//                    if ($data->is_verified == 1) {
//                        $update = TRUE;
//                        $insert = TRUE;
//                    } elseif (in_array($data->dcs_code, $verified_vsp)) {
//                        $update = TRUE;
//                        $cnt++;
//                    }
//                } else {
//                    $update = TRUE;
//                    $insert = TRUE;
//                }
//            } elseif (in_array($data->dcs_code, $verified_vsp)) {
//                $update = TRUE;
//                $cnt++;
//            }
            // if ($update) {
            if (in_array($data->member_code, $model->member_code)) {
                $historyModel = new TblMemberPaymentHistory();
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
                // if ($insert && $data->final_amount > 0) {
                $data->transfer_mode = '0';
                $payment_tr = new TblPaymentTransaction();
                $payment_tr->attributes = $data->attributes;
                $payment_tr->mobile_no = $data->memberCode->mobile_no;
                $payment_tr->is_file = 0;
                $payment_tr->payment_transaction_code = $payment_tr->getMaxCode() + $code_cnt;
                $payment_tr->code = $data->member_code;
                $payment_tr->name = $data->memberCode->member_name;
                $payment_tr->type = 'member';
                $payment_tr->member_count = 1;
                $save_model[] = $payment_tr;
                $cnt++;
                // } else {
                //   $data->transfer_mode = '1';
                //    $data->vsp_payment_reference_no = $verified_vsp_refno[$data->dcs_code];
                // }
                $data->payment_transaction_code = $payment_tr->payment_transaction_code;
                $code_cnt++;
                $save_model[] = $data;
                $farmer_cnt[$data->dcs_code]['pay'] = isset($farmer_cnt[$data->dcs_code]['pay']) ? $farmer_cnt[$data->dcs_code]['pay'] + 1 : 1;
                //}
            }
            $farmer_cnt[$data->dcs_code]['actual'] = array_count_values(array_column($query, 'dcs_code'))[$data->dcs_code];
        }
        foreach ($farmer_cnt as $key => $value) {
            if (!in_array($key, $verified_vsp) && isset($value['pay']) && $value['pay'] == $value['actual']) {
                $dcsPayment = TblDcsPayment::find()->where(['union_code' => $model->union_code, 'dcs_code' => $key, 'dcs_payment_cycle_code' => $model->dcs_payment_cycle_code])->one();
                $dcsPayment->status = 'sent';
                $save_model[] = $dcsPayment;
                $society_cnt++;
            }
        }
        $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' Member of ' . $society_cnt . ' Society', 'info']);
        if ($transaction != FALSE && $transaction != 'customRender') {
            $file_data = new TblPaymentTransaction();
            $file_data->type = 'member';
            $file_data = $file_data->getRecords();
            if (count($file_data) > 0) {
                $proccess_data = FALSE;
                if ($union_bank->server_type == 'eipl' && Yii::$app->general->checkDirectory($filePath) && Yii::$app->general->checkDirectory($filePath . 'inprocess') && Yii::$app->general->checkDirectory($filePath . 'inputfile') && Yii::$app->general->checkDirectory($filePath . 'mis')) {
                    $proccess_data = TRUE;
                    $bank_type = 'UBI';
                    $file_folder = $filePath . 'inputfile/';
                } else if (Yii::$app->general->checkDirectory($filePath)) {
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
                                $ubi_text.=$txtrow . PHP_EOL;
                            } else {
                                $text.=$txtrow . PHP_EOL;
                            }
                        } else if ($bank_type == 'AXIS') {
                            $char = (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) ? 'I' : 'N';
                            $txtrow = $char . '|' . //Record Identifier
                                    $row->code . '|' . //Member code
                                    $row->payment_transaction_code . '|' . //Payment Cycleid/ Payment Referenceid
                                    $row->name . '|' . //Member Name
                                    $row->bank_account_no . '|' . //Member Acc No
                                    $row->final_amount . '|' . //Amount
                                    $row->ifsc . '|' . //Memebr IFSC
                                    date('d-M-Y'); // Date
                            $text.=$txtrow . ',';
                            $bank_total_dabit = $bank_total_dabit + $row->final_amount;
                            $bank_total_cnt +=1;
                        }
                    }

                    $save_model = [];
                    $NEFT = TRUE;
                    $UBI = TRUE;
                    if ($text != '') {
                        $data_write = FALSE;
                        if ($bank_type == 'AXIS') {
                            $fileName = $file_folder . 'NEFT_' . date('YmdHis') . ".xlsx";
                            $first_row = 'D|0' . //Record Identifier
                                    rand(10000000000, 99999999999) . '|' . //Reference number
                                    $union_bank->bank_account_no . '|' . //Debit Account No
                                    $bank_total_dabit . '|' . //Amount
                                    $bank_total_cnt; // Total Count
                            $text = $first_row . ',' . $text;
                            $text = explode(',', $text);
                            unset($text[count($text) - 1]);
                            $rowCount = 2;
                            $objPHPExcel = new PHPExcel();
                            $objPHPExcel->setActiveSheetIndex(0);
                            $objPHPExcel->getDefaultStyle()
                                    ->getNumberFormat()
                                    ->setFormatCode(
                                            \PHPExcel_Style_NumberFormat::FORMAT_TEXT
                            );
                            $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Record Identifier");
                            $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Reference number");
                            $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Debit Account No");
                            $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Amount");
                            $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Transaction");

                            foreach ($text as $fields) {
                                $line = explode('|', $fields);
                                $data_write = TRUE;
                                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $line[0]);
                                // $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $line[1]);
                                //$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $line[2]);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $line[1], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $line[2], \PHPExcel_Cell_DataType::TYPE_STRING);
                                if ($rowCount == 2) {
                                    $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)
                                            ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
                                }
                                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $line[3]);
                                //$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $line[4]);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowCount, $line[4], \PHPExcel_Cell_DataType::TYPE_STRING);
                                if ($rowCount > 2) {
                                    $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)
                                            ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
//                                    $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)
//                                            ->getNumberFormat()->setFormatCode('dd-mmm-yyyy');
                                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $line[5]);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $line[6]);
//                                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $line[7]);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $rowCount, $line[7], \PHPExcel_Cell_DataType::TYPE_STRING);
                                }
                                $rowCount++;
                            }
                            // $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                            $objWriter->save($fileName);
                            $cmd = "java -jar " . $union_bank->file_path . "AxisBankEnc.jar " . $union_bank->file_path . "AxisProperty.properties";
                            exec($cmd);
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
                            $save_model[] = $data;
                        }
                        if(!empty($union_bank->bank_mobile)){
                            $bank_mobiles = explode(',',$union_bank->bank_mobile);

                            $sms_text = 'Dear Sir, ';
                            $sms_text .= 'We have successfully sent a payment file for A/C: '.$union_bank->bank_account_no.' which has '.$bank_total_cnt.' no of transaction with Total Amount: '.$bank_total_dabit;

                            foreach($bank_mobiles as $bank_mobile){
                                $mobile = '91'.$bank_mobile;
                                Yii::$app->bsmartsms->sendSmsPOST($mobile, $sms_text);
                            }
                        }
                        if(!empty($union_bank->bank_email)){
                            $bank_emails = $union_bank->bank_email;
                            $email_subject = date('d-m-Y').': Payment file sent to bank through Portal.';

                            $email_text = 'Dear Sir, <br /><br />';
                            $email_text .= 'We have successfully sent a payment file for '.$union_bank->unionCode->union_name.'  - '.$union_bank->bank_account_no.' Details are as below<br /><br />';
                            $email_text .= 'Transaction Amount :- '.$bank_total_dabit.'<br />';
                            $email_text .= 'File Name :- '.substr($fileName,strrpos($fileName,"/") + 1).'<br />';
                            $email_text .= 'No oF transaction :- '.$bank_total_cnt.'<br /><br />';
                            $email_text .= 'Please take a necessary Actions at your end.<br /><br />';
                            $email_text .= 'Regards,<br />';
                            $email_text .= $union_bank->unionCode->union_name.'.<br /><br />';
                            $email_text .= 'Note:- This is system generated email, Please do not reply.';
                            Yii::$app->general->sendEmail($email_subject, $email_text, $bank_emails);
                        }
                        $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' Member of ' . count($farmer_cnt) . ' Society', 'info']);
                        $flag = true;
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
        return $flag;
    }

    public function actionPaymentCycleList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $paymentcycleModel = new TblDcsPaymentCycle();
            $list = $paymentcycleModel->unionPaymentCycles($unionCode);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo Json::encode(['output' => $out]);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionPaymentCycleListWithDate() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $paymentcycleModel = new TblDcsPaymentCycle();
            $list = $paymentcycleModel->unionPaymentCycles($unionCode);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $r . ':' . $key,
                    'name' => $r);
            }
            echo Json::encode(['output' => $out]);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionConfirmPayment() {
        if (Yii::$app->request->post()) {
            $model = new TblMemberPayment();
            if (isset($_REQUEST['selection'])) {
                $model->load(Yii::$app->request->post());
                if (!empty($model->dcs_payment_cycle_code)) {
                    if (Yii::$app->request->post('flag') == 'member') {
                        $model->dcs_code = Yii::$app->request->post('selection');
                        $query = $model->find()->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'dcs_code' => $model->dcs_code])
                                ->all();

                        if (Yii::$app->session->get('makerChecker') == 1) {
                            $payment_cnt = $model->find()->select(['member_code'])->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'dcs_code' => $model->dcs_code])
                                    ->andWhere(['<>', 'ifsc', ''])->andWhere(['is not', 'ifsc', NULL])
                                    ->andWhere(['<>', 'bank_account_no', ''])->andWhere(['is not', 'bank_account_no', NULL])
                                    ->andWhere(['is_verified' => [1]])
                                    ->count();
                        } else {
                            $payment_cnt = $model->find()->select(['member_code'])->where(['dcs_payment_cycle_code' => $model->dcs_payment_cycle_code, 'status' => ['processed', 'rejected'], 'dcs_code' => $model->dcs_code])
                                    ->andWhere(['<>', 'ifsc', ''])->andWhere(['is not', 'ifsc', NULL])
                                    ->andWhere(['<>', 'bank_account_no', ''])->andWhere(['is not', 'bank_account_no', NULL])
                                    ->andWhere(['is_verified' => [0, 1]])
                                    ->count();
                        }

                        $dataProvider = new ArrayDataProvider([
                            'allModels' => $query,
                            'pagination' => FALSE
                        ]);

                        $tot_cnt = count($query);
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                            'message' => 'Out of (<b>' . $tot_cnt . '</b>) Farmer Payment of (<b>' . $payment_cnt . '</b>)  Farmer will be only done.<br/>']);
                        return $this->render('confirm-payment', [
                                    'searchModel' => $model,
                                    'dataProvider' => $dataProvider,
                        ]);
                    } else {
                        if ($this->exportCSV($model)) {
                            return $this->redirect(\yii\helpers\Url::previous());
                        }
                    }
                }
            } else {
                return $this->redirect(\yii\helpers\Url::previous());
            }
        }
    }

    public function actionCheckBank() {
        Yii::$app->session->set('payment_type', NULL);
        if (Yii::$app->request->post()) {
            $model = new TblUnionBankPayment();
            $model->union_code = Yii::$app->request->post('union_code');
            $model = $model->getRecord();
            if (!empty($model->file_path) && !empty($model->mobile_no)) {
                $status = 'success';
                $msg = '';
            } else {
                $status = 'error';
                $msg = 'Bank intigration not yet done.';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionCheckBankDcs() {
        Yii::$app->session->set('payment_type', NULL);
        if (Yii::$app->request->get()) {
            $model = new TblDcs();
            $model->attributes = Yii::$app->request->get();
            $status = 'success';
            $msg = '';
            Yii::$app->session->set('payment_type', Yii::$app->request->post('type'));
            if (Yii::$app->request->post('type') == 'member-vsp') {
                $verified_vsp = [];
                $uv_vsp = [];
                $query_vsp = TblDcs::find()
                        ->where(['dcs_code' => $model->dcs_code])
                        ->all();
                foreach ($query_vsp as $data) {
                    if (!empty($data->defaultBankDetail)) {
                        if (Yii::$app->session->get('makerChecker') == 1) {
                            $verified_dcs = new TblVerification();
                            $verified_dcs->module_field = 'bank_account_no';
                            $verified_dcs->module_id = $data->defaultBankDetail->detail_code;
                            $verified_dcs->module_name = 'TblBankDetails';
                            if (!empty($verified_dcs->getVerifiedBank())) {
                                $verified_vsp[] = $data->dcs_name;
                            } else {
                                $uv_vsp[] = $data->dcs_name;
                            }
                        } else {
                            $verified_vsp[] = $data->dcs_name;
                        }
                    } else {
                        $uv_vsp[] = $data->dcs_name;
                    }
                }
                $msg = '<b>Payment of following VSP will not be done.</b><br/>' . implode('<br/>', $uv_vsp) . '<br/><b>Payment of following VSP will be done.</b><br/>' . implode('<br/>', $verified_vsp) . '<br/><br/><center><b>Are you sure to processed ?</b></center>';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionSendOtp() {
        if (Yii::$app->request->post()) {
            Yii::$app->session->set('otp_id', NULL);
            $model = new TblUnionBankPayment();
            $model->union_code = Yii::$app->request->post('union_code');
            $model = $model->getRecord();
            $otp_model = new TblPaymentOtpVerification();
            $otp_model->attributes = $model->attributes;
            $otp_model->otp_code = rand(1000, 9999);
            if ($otp_model->save()) {
                Yii::$app->session->set('otp_id', $otp_model->id);
                //$mobile = '919712147065';
                $mobile = '91' . $model->mobile_no;
                $message = 'Your OTP for Payment is ' . $otp_model->otp_code;
                Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);
                $status = 'success';
                $msg = '';
            } else {
                $status = 'error';
                $msg = 'SMS Service Not Availabe.';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionVerifyOtp() {
        if (Yii::$app->session->get('otp_id') != NULL && Yii::$app->request->post()) {
            $msg = '';
            $otp_data = TblPaymentOtpVerification::findOne(Yii::$app->session->get('otp_id'));
            if ($otp_data->attempt < 3) {
                $otp_data->attempt+=1;
                if ($otp_data->otp_code == Yii::$app->request->post('otp_code')) {
                    $otp_data->status = 'Verified';
                    $status = 'success';
                } else {
                    $otp_data->status = 'Notverified';
                    $status = 'error';
                    $msg = 'Please Enter valid otp.';
                }
                $otp_data->save();
            } else {
                $status = 'error';
                $msg = 'You have already attempted 3 time.';
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode(['status' => $status, 'message' => $msg]);
        }
    }

    public function actionDisbursePayment() {
        $model = new TblMemberPayment();
        $model->load(Yii::$app->request->post());
        $model->member_code = Yii::$app->request->post('selection');
        if ($this->exportTxt(Yii::$app->session->get('payment_type'), $model)) {
            return $this->redirect(['export-payment-list']);
        }
    }

    public function actionPaymentAdjust() {
        $model = new TblMemberPayment();
        $model->attributes = Yii::$app->request->get();
        if (Yii::$app->request->post('TblMemberPayment')) {
            $adjust_id = Yii::$app->request->post('TblMemberPayment')['member_payment_code'];
            $adjust_amt = Yii::$app->request->post('TblMemberPayment')['adjust_amount'];
            $adjust_remark = Yii::$app->request->post('TblMemberPayment')['adjust_remark'];
            $save_model = [];
            $cnt = 0;
            foreach ($adjust_id as $key => $value) {
                if ($adjust_amt[$key] != 0 && $adjust_amt[$key] != '') {
                    $data = TblMemberPayment::findOne($adjust_id[$key]);
                    $historyModel = new TblMemberPaymentHistory();
                    Yii::$app->operation->history($data, $historyModel, UPDATE);
                    $data->adjust_amount = $adjust_amt[$key];
                    $data->adjust_remark = $adjust_remark[$key];
                    $data->final_amount = $data->final_amount + $adjust_amt[$key];
                    $save_model[] = $historyModel;
                    $save_model[] = $data;
                    $cnt++;
                }
            }
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment of ' . $cnt . ' farmer adjusted succesfully', 'info']);
            if ($transaction !== FALSE && $transaction != 'customRender') {
                return $this->redirect(['create']);
            }
        }
        $query = $model->getRecords();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        return $this->render('payment-adjust', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
