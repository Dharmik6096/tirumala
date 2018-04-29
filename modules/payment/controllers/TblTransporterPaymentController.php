<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblTransporterPayment;
use app\modules\payment\models\TblTransporterPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\modules\payment\models\TblVehiclePayment;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\payment\models\TblTransporterPaymentHistory;
use app\modules\payment\models\TblVehiclePaymentHistory;
use PHPExcel;
use app\modules\payment\models\TblPaymentTransaction;
use app\modules\payment\models\TblBankPaymentLog;
use app\modules\payment\models\TblUnionBankPayment;
use app\modules\transporter\models\TblVehicleKmInfo;
use app\modules\transporter\models\TblVehicleKmInfoHistory;

/**
 * TblTransporterPaymentController implements the CRUD actions for TblTransporterPayment model.
 */
class TblTransporterPaymentController extends \app\controllers\ChildController {

    /**
     * @inheritdoc
     */
    public $layout = "@app/themes/pcdf/layouts/paymentLayout.php";

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblTransporterPayment models.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblTransporterPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTransporterPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblTransporterPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex() {
        $this->model = new TblTransporterPayment();

        $data = Yii::$app->request->queryParams;
        $dataProvider = [];
        if (!empty($data)) {
            $bmc_code = $data['TblTransporterPayment']['bmc_code'];
            $union_code = $data['TblTransporterPayment']['union_code'];
            $from_date = $data['TblTransporterPayment']['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $data['TblTransporterPayment']['to_date'];
            $to_date = date('Y-m-d', strtotime($to_date));
            $result = \Yii::$app->db->createCommand("{CALL [sp_transporter_list](:union_code,:bmc_code,:from_date,:to_date)}")
                    ->bindValue(':union_code', $union_code)
                    ->bindValue(':bmc_code', $bmc_code)
                    ->bindValue(':from_date', $from_date)
                    ->bindValue(':to_date', $to_date);
            $query = $result->queryAll();
            if (!empty($query)) {
                $dataProvider = $query;
            }
        }
        $this->viewFile = 'index';

        return $this->render('create', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblTransporterPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transporter_payment_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblTransporterPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblTransporterPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTransporterPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTransporterPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTransporterPayment() {
        if (Yii::$app->request->post()) {
            $this->model = new TblTransporterPayment();
            $tr_data = Yii::$app->request->post();
            $transporter_data = $tr_data['TblTransporterPayment'];
            $bmc_code = $transporter_data['bmc_code'];
            $union_code = $transporter_data['union_code'];
            $transporters = ',' . implode(',', $transporter_data['transporter_code']) . ',';
            $from_date = $transporter_data['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $transporter_data['to_date'];
            $to_date = date('Y-m-d', strtotime($to_date));

            $result = \Yii::$app->db->createCommand("{CALL [sp_transporter_summary_temp](:bmc_code,:transporter_code,:from_date,:to_date)}")
                    ->bindValue(':bmc_code', $bmc_code)
                    ->bindValue(':transporter_code', $transporters)
                    ->bindValue(':from_date', $from_date)
                    ->bindValue(':to_date', $to_date);
            $query = $result->queryAll();
            $this->model->remarks = '';
            $dataProvider = new ArrayDataProvider([
                'allModels' => $query,
                'sort' => [
                    'defaultOrder' => ['transporter_name' => SORT_ASC],
                    'attributes' => [
                        'transporter_code',
                        'transporter_name',
                        'bmc_code',
                        'bmc_name',
                        'total_vehicle',
                        'coll_qty',
                        'coll_kg_fat',
                        'coll_kg_snf',
                        'disp_qty',
                        'disp_kg_fat',
                        'disp_kg_snf',
                        'rec_qty',
                        'rec_kg_fat',
                        'rec_kg_snf',
                        'cd_qty_diff',
                        'cd_kg_fat_diff',
                        'cd_kg_snf_diff',
                        'rd_qty_diff',
                        'rd_kg_fat_diff',
                        'rd_kg_snf_diff',
                        'no_of_days',
                        'total_amount',
                        'total_deduction',
                        'final_amount',
                        'adjust_amount',
                        'net_amount',
                        'remarks',
                    ],
                ],
            ]);
            $this->model->from_date = $from_date;
            $this->model->to_date = $to_date;
            $this->model->bmc_code = $bmc_code;
            $this->model->union_code = $union_code;
            return $this->render('transporter_payment', [
                        'model' => $this->model,
                        'dataProvider' => $dataProvider
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionTransporterSummary() {
//        echo "test";
        $bmc_code = Yii::$app->request->post('bmc_code');
        $transporter_code = ',' . Yii::$app->request->post('transporter_code') . ',';
        $from_date = Yii::$app->request->post('from_date');
        $to_date = Yii::$app->request->post('to_date');
        $result = \Yii::$app->db->createCommand("{CALL [sp_transporter_summary_temp](:bmc_code,:transporter_code,:from_date,:to_date)}")
                ->bindValue(':bmc_code', $bmc_code)
                ->bindValue(':transporter_code', $transporter_code)
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date);
        $query['list'] = $result->queryAll();
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $query]);
//        $paymentcycleModel = new TblDcsPaymentCycleApplicability();
//        $society_list = $paymentcycleModel->societyList(Yii::$app->request->post('payment_cycle'));
//        Yii::$app->response->format = trim(Response::FORMAT_JSON);
//        return Json::encode(['status' => 'success', 'data' => $society_list]);
    }

    public function actionSaveTransporterPayment() {
        $this->viewFile = 'index';
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $transporter_payment_data = [];
            $vehicle_payment_data = [];
            $i = 0;
            $delete_model = [];
            foreach ($data['TblTransporterPayment'] as $transporter_data) {
                $transporter_model = new TblTransporterPayment();
                $transporter_payment_code = Yii::$app->general->getCodeAutoIncrement($transporter_model) + $i;
//                $transporter_model->transporter_payment_code=$transporter_payment_code;
                $transporter_model->attributes = $transporter_data;
                $transporter_model->bank_name = isset($transporter_model->transporterCode->bankCode) ? $transporter_model->transporterCode->bankCode->bank_name : '';
                $transporter_model->bank_code = isset($transporter_model->transporterCode) ? $transporter_model->transporterCode->bank_code : '';
                $transporter_model->branch_name = isset($transporter_model->transporterCode->branchCode) ? $transporter_model->transporterCode->branchCode->branch_name : '';
                $transporter_model->branch_code = isset($transporter_model->transporterCode) ? $transporter_model->transporterCode->branch_code : '';
                $transporter_model->ifsc = isset($transporter_model->transporterCode) ? $transporter_model->transporterCode->ifsc : '';
                $transporter_model->bank_account_no = isset($transporter_model->transporterCode) ? $transporter_model->transporterCode->bank_account_no : '';
                $transporter_model->status = 'processed';
                $adjust_amt = !empty($transporter_model->adjust_amount) ? $transporter_model->adjust_amount : 0;
                $transporter_model->final_amount = $transporter_model->final_amount + $adjust_amt;
                $transporter_model->is_verified = isset($transporter_model->verifiedBank) ? 1 : (isset($transporter_model->rejectedBank) ? 2 : 0);

                $transporter_model->validate();
                $ex_tr_data = $transporter_model->existData($transporter_model);

                $delete_vehicle = new TblVehiclePayment();
                if (!empty($ex_tr_data)) {
                    foreach ($ex_tr_data as $exist_data) {
                        $historyModel = new TblTransporterPaymentHistory();
                        Yii::$app->operation->history($exist_data, $historyModel, DELETE);
                        $delete_model[] = $historyModel;
                        $delete_model[] = $exist_data;

                        $ex_veh_data = $delete_vehicle->existData($exist_data);
                        foreach ($ex_veh_data as $vehicle) {
                            $historyModel = new TblVehiclePaymentHistory();
                            Yii::$app->operation->history($vehicle, $historyModel, DELETE);
                            $delete_model[] = $historyModel;
                            $delete_model[] = $vehicle;
                        }
                    }
                }
                $transporter_payment_data[] = $transporter_model;
                $transporter_code = $transporter_data['transporter_code'];
                foreach ($data['TblVehiclePayment'][$transporter_code] as $vehicle_data) {
                    $vehicle_model = new TblVehiclePayment();
                    $selected_vehicle = isset($data['selection']) ? $data['selection'] : [];
                    if (in_array($vehicle_data['vehicle_code'], $selected_vehicle)) {
                        $vehicle_model->transporter_payment_code = $transporter_payment_code;
                        $vehicle_model->attributes = $vehicle_data;
                        $vehicle_model->status = 'processed';
                        $vehicle_model->validate();
                        $vehicle_payment_data[] = $vehicle_model;
                    }
                }
                $i++;
            }

            $record = $this->generalModel->appTransaction($delete_model, ['Update', 'edit']);
            if ($record == 'customRedirect') {
                $transaction = $this->generalModel->saveTransaction($transporter_payment_data, $vehicle_payment_data, ['Transporter Payment', 'create']);
                //            die;
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
    }

    public function actionPaymentDisburse() {
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->get());
        $query = [];
        if (isset($model->transporter_payment_cycle) && $model->transporter_payment_cycle != '') {
            $date = explode(' to ', $model->transporter_payment_cycle);
            $from_date = date('Y-m-d', strtotime($date[0]));
            $to_date = date('Y-m-d', strtotime($date[1]));
            $query = $model->find()->where(['from_date' => $from_date, 'to_date' => $to_date])
                            ->andWhere(['>', 'final_amount', 0])
                            ->joinWith(['transporterCode', 'bmcCode'])
                            ->asArray()->all();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => 'Transporter Payment Disburse : Step 1'
        ]);
    }

    public function actionPaymentCycleList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $paymentcycleModel = new TblTransporterPayment();
            $list = $paymentcycleModel->transporterPaymentCycles($unionCode);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $r,
                    'name' => $r);
            }
            echo Json::encode(['output' => $out]);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionPaymentVehicles($transporter_payment_code) {
        $model = new TblVehiclePayment();
        if (!empty($transporter_payment_code)) {
            $query = $model->find()
                            ->where(['transporter_payment_code' => $transporter_payment_code, 'status' => ['processed', 'rejected']])
                            ->joinWith(['transporterCode', 'bmcCode'])
                            ->asArray()->all();
        } else {
            $query = $model->find()->where('0=1')->asArray()->all();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
        ]);
        return $this->render('vehicle_index', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionConfirmPayment() {
        if (Yii::$app->request->post()) {
            $model = new TblTransporterPayment();
            if (isset($_REQUEST['selection'])) {
                $model->load(Yii::$app->request->post());
                $model->transporter_payment_code = Yii::$app->request->post('selection');
                if (!empty($model->transporter_payment_cycle)) {
                    if (Yii::$app->request->post('flag') == 'tp') {
                        $query = $model->find()->where(['transporter_payment_code' => $model->transporter_payment_code])
                                ->all();
                        if (Yii::$app->session->get('makerChecker') == 1) {
                            $payment_cnt = $model->find()->select(['transporter_code'])->where(['transporter_payment_code' => $model->transporter_payment_code])
                                    ->andWhere(['<>', 'ifsc', ''])->andWhere(['is not', 'ifsc', NULL])
                                    ->andWhere(['<>', 'bank_account_no', ''])->andWhere(['is not', 'bank_account_no', NULL])
                                    ->andWhere(['is_verified' => [1]])
                                    ->count();
                        } else {
                            $payment_cnt = $model->find()->select(['transporter_code'])->where(['transporter_payment_code' => $model->transporter_payment_code])
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
                            'message' => 'Out of (<b>' . $tot_cnt . '</b>) Transporter Payment of (<b>' . $payment_cnt . '</b>)  Transporter will be only done.<br/>']);
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

    public function actionBankPayment() {
        $model = new TblTransporterPayment();
        $model->load(Yii::$app->request->post());
        $model->transporter_payment_code = Yii::$app->request->post('selection');
        $this->exportTxt($model);
        return $this->redirect(['payment-disburse']);
    }

    protected function exportCSV($model) {
        $newModel = new TblTransporterPayment();
        $query = $newModel->find()->where(['transporter_payment_code' => $model->transporter_payment_code])
                ->joinWith(['transporterCode', 'bmcCode'])
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
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Transporter Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Transporter Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'BMC Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'BMC Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Account No');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Bank');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Branch');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'IFSC');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Total Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Total Deduction');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Adjsut Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'Final Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'Adjsut Remarks');
        foreach ($query as $row) {
            if ($row->final_amount > 0) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->transporter_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->transporterCode->transporter_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->bmc_code);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->bmcCode->bmc_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, '="' . $row->bank_account_no . '"');
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->bank_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->branch_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row->ifsc);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row->total_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row->total_deduction);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row->adjust_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row->final_amount);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row->remarks);
            }
        }
        $fileName = "tp_payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    protected function exportTxt($model) {
        $flag = FALSE;
        $union_bank = TblUnionBankPayment::find()->where(['union_code' => $model->union_code])->one();
        if ($union_bank->server_type == 'eipl') {
            $filePath = $union_bank->file_path . date('Y-m-d') . '/';
        } else {
            $filePath = $union_bank->file_path . 'in/';
        }
        $code_cnt = 0;
        $cnt = 0;
        $newModel = new TblTransporterPayment();
        $query = $newModel->find()->where(['transporter_payment_code' => $model->transporter_payment_code])
                ->all();
        foreach ($query as $data) {
            if (in_array($data->transporter_payment_code, $model->transporter_payment_code)) {
                $historyModel = new TblTransporterPaymentHistory();
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
                $payment_tr->mobile_no = $data->transporterCode->mobile_no;
                $payment_tr->is_file = 0;
                $payment_tr->payment_transaction_code = $payment_tr->getMaxCode() + $code_cnt;
                $payment_tr->code = $data->transporter_code;
                $payment_tr->name = $data->transporterCode->transporter_name;
                $payment_tr->type = 'tp';
                $payment_tr->member_count = 1;
                $payment_tr->dcs_payment_cycle_code = (int) $model->transporter_payment_code;
                $save_model[] = $payment_tr;
                $cnt++;
                $data->payment_transaction_code = $payment_tr->payment_transaction_code;
                $code_cnt++;
                $save_model[] = $data;
                $vehicle = new TblVehicleKmInfo();
                $vehicle->transporter_code = $data->transporter_code;
                $data_lock = $vehicle->getVehicleList($data->from_date, $data->to_date);
                foreach ($data_lock as $record) {
                    $historyModel = new TblVehicleKmInfoHistory();
                    Yii::$app->operation->history($record, $historyModel, UPDATE);
                    $record->data_lock = 1;
                    $save_model[] = $historyModel;
                    $save_model[] = $record;
                }
            }
        }
        $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' Transporter out of ' . count($query) . ' Transporter', 'info']);
        if ($transaction != FALSE && $transaction != 'customRender') {
            $file_data = new TblPaymentTransaction();
            $file_data->type = 'tp';
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
                                    $row->ifsc . '|' . //TP IFSC
                                    $row->payment_transaction_code . '|' . //Payment Cycleid/ Payment Referenceid
                                    $row->bank_account_no . '|' . //TP Acc No
                                    $row->name . '|' . //TP Name
                                    $row->code . '|' . //TP code
                                    '' . '|' . //Aadhar No
                                    substr($row->bank_name, 0, 35) . '|' . //Bank Name
                                    $model->union_code . '|' . //Union Code
                                    $row->code . '|' . //DCS Code
                                    $union_bank->bank_account_no . '|' . //Charge Debit A/c
                                    'SMS' . '|' . //Mobile/ Email ID
                                    $mobile_no; //mobile no 
                            if (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) {
                                $ubi_text.=$txtrow . PHP_EOL;
                            } else {
                                $text.=$txtrow . PHP_EOL;
                            }
                        } else if ($bank_type == 'AXIS') {
                            $char = (substr($union_bank->ifsc, 0, 4) == substr($row->ifsc, 0, 4)) ? 'I' : 'N';
                            $txtrow = $char . '|' . //Record Identifier
                                    $row->code . '|' . //TP code
                                    $row->payment_transaction_code . '|' . //Payment Cycleid/ Payment Referenceid
                                    $row->name . '|' . //TP Name
                                    $row->bank_account_no . '|' . //TP Acc No
                                    $row->final_amount . '|' . //Amount
                                    $row->ifsc . '|' . //TP IFSC
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
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $line[1], \PHPExcel_Cell_DataType::TYPE_STRING);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $rowCount, $line[2], \PHPExcel_Cell_DataType::TYPE_STRING);
                                if ($rowCount == 2) {
                                    $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)
                                            ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
                                }
                                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $line[3]);

                                $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $rowCount, $line[4], \PHPExcel_Cell_DataType::TYPE_STRING);
                                if ($rowCount > 2) {
                                    $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)
                                            ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

                                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $line[5]);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $line[6]);
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $rowCount, $line[7], \PHPExcel_Cell_DataType::TYPE_STRING);
                                }
                                $rowCount++;
                            }
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
                        $transaction = $this->generalModel->saveTransaction($save_model, ['Payment disbursed for ' . $cnt . ' Transporter out of ' . count($query) . ' Transporter', 'info']);
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

}
