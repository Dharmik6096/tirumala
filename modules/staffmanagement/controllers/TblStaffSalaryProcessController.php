<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffSalaryProcess;
use app\modules\staffmanagement\models\TblStaffSalaryProcessSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\globalmaster\models\TblSalaryHeads;
use app\modules\staffmanagement\models\TblStaffSalary;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\staffmanagement\models\TblStaffSalaryTransaction;
use app\modules\staffmanagement\models\TblStaffAttendance;
use app\modules\staffmanagement\models\TblStaffAdditionDeduction;
use app\modules\staffmanagement\models\TblStaffInstallment;
use app\modules\staffmanagement\models\TblStaffSalaryProcessTransaction;
use app\modules\staffmanagement\models\TblStaffSalaryProcessHistory;
use app\modules\staffmanagement\models\TblStaffSalaryProcessTransactionHistory;
use app\modules\staffmanagement\models\TblStaffSalaryProcessTransactionSearch;
use app\modules\staffmanagement\models\TblStaffSalaryHoldDue;
use app\modules\staffmanagement\models\TblStaffSalaryHoldDueHistory;

/**
 * TblStaffSalaryProcessController implements the CRUD actions for TblStaffSalaryProcess model.
 */
class TblStaffSalaryProcessController extends \app\controllers\ChildController {

    /**
     * Lists all TblStaffSalaryProcess models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffSalaryProcessSearch();
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffSalaryProcess model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $trModel = new TblStaffSalaryProcessTransactionSearch();
        $trModel->salary_code = $id;
        $trDataProvider = $trModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'trModel' => $trModel, 'trDataProvider' => $trDataProvider,
        ]);
    }

    /**
     * Creates a new TblStaffSalaryProcess model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = '') {
        $this->model = new TblStaffSalaryProcess();
        $searchModel = new TblStaffSalaryProcessSearch();
        $processData = Yii::$app->request->post('TblStaffSalaryProcess');
        $month = !empty($processData) ? date('01-') . $processData['month'] : '';
        $searchModel->month = !empty($month) ? date('Y-m-d', strtotime($month)) : NULL;
        $searchModel->union_code = !empty($processData['union_code']) ? $processData['union_code'] : NULL;

        $dataProvider = $searchModel->search(Yii::$app->request->post());

        $memberModel = new TblStaffSalary();
        $memberData = $memberModel->getStaffMember($searchModel->month, $searchModel->union_code);
        $date = !empty($processData['disbursement_date']) ? date('Y-m-d', strtotime($processData['disbursement_date'])) : NULL;
        $this->model->month = $searchModel->month;
        $this->model->disbursement_date = $date;
        $this->model->union_code = $searchModel->union_code;
        if (empty($type)) {
            $this->model->scenario = 'process';
        } else {
            $this->model->scenario = 'disburse';
        }
        $modelSave = [];
        if (Yii::$app->request->post()) {
            $msg = 'Staff Salary Process';
            if ($this->model->validate() && !empty($memberData)) {
                $update = FALSE;
                $i = 1;
                $inc = 1;
                foreach ($memberData as $member) {
                    $existData = $this->model->getExistData($member->staff_member_code);
                    $TotalDays = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($month)));
                    $attendanceModel = new TblStaffAttendance();
                    $leave = $attendanceModel->getMemberAttendance($member->staff_member_code, $searchModel->month);
                    $masterModel = new TblStaffSalaryProcess();
                    if (!empty($existData->salary_code)) {
                        $mainModel = $masterModel->findOne($existData->salary_code);
                        $HistoryModel = new TblStaffSalaryProcessHistory();
                        Yii::$app->operation->history($mainModel, $HistoryModel, 'UPDATE');
                        $modelSave[] = $HistoryModel;
                        $update = TRUE;
                        if (!empty($type)) {
                            $update = FALSE;
                            $msg = 'Staff Salary Disbursed';
                            $mainModel->disbursement_date = $date;
                            if (!empty($processData['staff_member_code'])) {
                                foreach ($processData['staff_member_code'] as $key => $value) {
                                    if ($value == $member->staff_member_code) {
                                        $mainModel->previous_hold = Yii::$app->general->getforeignkey($mainModel->holdDue, 'hold_amount');
                                        $mainModel->previous_due = Yii::$app->general->getforeignkey($mainModel->holdDue, 'due_amount');
                                        $mainModel->hold_amount = $processData['hold_amount'][$key];
                                        $mainModel->additional_pay = $processData['additional_pay'][$key];

                                        $dueholdModel = new TblStaffSalaryHoldDue();
                                        $existHoldDue = $dueholdModel->find()->where(['staff_member_code' => $member->staff_member_code])->one();
                                        if (!empty($existHoldDue)) {
                                            $HistoryModel = new TblStaffSalaryHoldDueHistory();
                                            Yii::$app->operation->history($existHoldDue, $HistoryModel, 'UPDATE');
                                            $modelSave[] = $HistoryModel;
                                            $existHoldDue->hold_amount = $mainModel->hold_amount;
                                            $existHoldDue->due_amount = $mainModel->additional_pay;
                                            $dueholdModel = $existHoldDue;
                                        } else {
                                            $dueholdModel->staff_member_code = $mainModel->staff_member_code;
                                            $dueholdModel->hold_amount = $mainModel->hold_amount;
                                            $dueholdModel->due_amount = $mainModel->additional_pay;
                                        }
                                        $modelSave[] = $dueholdModel;
                                    }
                                }
                            }
                        }
                        $masterModel = $mainModel;
                    } else {
                        $masterModel->salary_code = (string) Yii::$app->general->getCodeAutoIncrement($masterModel, $i);
                        $i++;
                    }
                    $masterModel->staff_member_code = $member->staff_member_code;
                    $masterModel->effective_working_days = $TotalDays;
                    $masterModel->lwp = $leave;
                    $masterModel->month = $searchModel->month;
                    $masterModel->designation_code = Yii::$app->general->getforeignkey($member->staffMemberCode, 'designation_code');
                    $masterModel->account_no = Yii::$app->general->getforeignkey($member->staffMemberCode, 'bank_account_no');
                    $masterModel->bank_code = Yii::$app->general->getforeignkey($member->staffMemberCode, 'bank_code');
                    $masterModel->branch_code = Yii::$app->general->getforeignkey($member->staffMemberCode, 'branch_code');
                    $masterModel->union_code = $member->union_code;
                    $masterModel->actual_value = $member->addition - $member->deduction;

                    $netpayble = 0;
                    $addition = 0;
                    $deduction = 0;
                    $leavededuction = 0;
                    //staff addition deduction salary calculation
                    $addDeductModel = new TblStaffAdditionDeduction();
                    $addDeductData = $addDeductModel->getStaffAddDed($member->staff_member_code);
                    if (!empty($addDeductData)) {
                        foreach ($addDeductData as $addded) {
                            $installModel = new TblStaffInstallment();
                            $installment = $installModel->getStaffInstallment($addded->staff_addition_deduction_no, $masterModel->month);
                            if ($addded->type == 1) {
                                $addition = $installment;
                                $netpayble = $netpayble + $installment;
                            } else {
                                $deduction = $installment;
                                $netpayble = $netpayble - $installment;
                            }
                        }
                    }

                    //staff head wise and attendance wise salary calculation
                    $headModel = new TblSalaryHeads();
                    $headData = $headModel->getallHead();
                    if (!empty($headData)) {
                        foreach ($headData as $heads) {
                            $transactionModel = new TblStaffSalaryProcessTransaction();
                            if (!empty($existData->salary_code)) {
                                $existtrData = $transactionModel->find()->where(['salary_code' => $existData->salary_code, 'salary_head_code' => $heads->salary_head_code])->one();
                                if (!empty($existtrData)) {
                                    $HistoryModel = new TblStaffSalaryProcessTransactionHistory();
                                    Yii::$app->operation->history($existtrData, $HistoryModel, 'UPDATE');
                                    $modelSave[] = $HistoryModel;
                                    $transactionModel = $existtrData;
                                }
                            } else {
                                $transactionModel->salary_transaction_code = (string) Yii::$app->general->getCodeAutoIncrement($transactionModel, $inc);
                                $inc++;
                            }
                            $transactionModel->salary_code = $masterModel->salary_code;
                            $transactionModel->salary_head_code = $heads->salary_head_code;
                            $transactionModel->type_of_head = $heads->salary_head_type;
                            $transactionModel->union_code = $masterModel->union_code;

                            $headtype = $heads->salary_head_type;
                            $transModel = new TblStaffSalaryTransaction();
                            $transData = $transModel->getSalaryTrans($member->staff_salary_code, $heads->salary_head_code);
                            if (!empty($transData)) {
                                $head = $transData->value;
                                $lwp = $transData->lwp_effect;
                                $transactionModel->actual_value = $head;

                                if ($headtype == 1) {
                                    if (!empty($leave) && $lwp == 1) {
                                        $headValue = $head - (($head / $TotalDays) * $leave);
                                        $leavededuction = $leavededuction + $head - $headValue;
                                        $netpayble = $netpayble + $headValue;
                                        $transactionModel->value = $headValue;
                                    } else {
                                        $netpayble = $netpayble + $head;
                                        $transactionModel->value = $head;
                                    }
                                } else {
                                    $netpayble = $netpayble - $head;
                                    $transactionModel->value = $head;
                                }
                            }
                            $modelSave[] = $transactionModel;
                        }
                        $defaultHead = $headModel->getallHead(1);
                        foreach ($defaultHead as $dhead) {
                            $defaModel = new TblStaffSalaryProcessTransaction();
                            if (!empty($existData->salary_code)) {
                                $existDefData = $defaModel->find()->where(['salary_code' => $existData->salary_code, 'salary_head_code' => $dhead->salary_head_code])->one();
                                if (!empty($existDefData)) {
                                    $HistoryModel = new TblStaffSalaryProcessTransactionHistory();
                                    Yii::$app->operation->history($existDefData, $HistoryModel, 'UPDATE');
                                    $modelSave[] = $HistoryModel;
                                    $defaModel = $existDefData;
                                }
                            } else {
                                $defaModel->salary_transaction_code = (string) Yii::$app->general->getCodeAutoIncrement($defaModel, $inc);
                                $defaModel->salary_code = $masterModel->salary_code;
                                $defaModel->salary_head_code = $dhead->salary_head_code;
                                $defaModel->type_of_head = $dhead->salary_head_type;
                                $defaModel->union_code = $masterModel->union_code;
                                $inc++;
                            }
                            if ($dhead->salary_head_code == 91) {
                                $pDue = empty($mainModel->previous_due) ? 0 : $mainModel->previous_due;
                                $pHold = empty($mainModel->previous_hold) ? 0 : $mainModel->previous_hold;
                                $cDue = empty($mainModel->additional_pay) ? 0 : $mainModel->additional_pay;
                                $cHold = empty($mainModel->hold_amount) ? 0 : $mainModel->hold_amount;
                                $netAmount = $netpayble - $pDue + $pHold;
                                $totalAmount = $netAmount + $cDue - $cHold;
                                $defaModel->value = $totalAmount;
                                $defaModel->actual_value = $masterModel->actual_value;
                            } elseif ($dhead->salary_head_code == 92) {
                                $defaModel->value = $leavededuction;
                                $defaModel->actual_value = $leavededuction;
                            } elseif ($dhead->salary_head_code == 93) {
                                $defaModel->value = $deduction;
                                $defaModel->actual_value = $deduction;
                            } elseif ($dhead->salary_head_code == 94) {
                                $defaModel->value = $addition;
                                $defaModel->actual_value = $addition;
                            } elseif ($dhead->salary_head_code == 95) {
                                $defaModel->value = $netpayble;
                                $defaModel->actual_value = $netpayble;
                            }
                            $modelSave[] = $defaModel;
                        }
                    }
                    $pDue = empty($mainModel->previous_due) ? 0 : $mainModel->previous_due;
                    $pHold = empty($mainModel->previous_hold) ? 0 : $mainModel->previous_hold;
                    $cDue = empty($mainModel->additional_pay) ? 0 : $mainModel->additional_pay;
                    $cHold = empty($mainModel->hold_amount) ? 0 : $mainModel->hold_amount;

                    $netAmount = $netpayble - $pDue + $pHold;
                    $totalAmount = $netAmount + $cDue - $cHold;
                    $masterModel->value = $totalAmount;
                    $modelSave[] = $masterModel;
                }
                $transaction = $this->generalModel->saveTransaction($modelSave, [$msg, ($update) ? 'edit' : 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblStaffSalaryProcess model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->salary_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblStaffSalaryProcess model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffSalaryProcess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStaffSalaryProcess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffSalaryProcess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProcessGrid() {
        $searchModel = new TblStaffSalaryProcessSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblStaffSalaryProcess'));
        $month = !empty(Yii::$app->request->get('TblStaffSalaryProcess')['month']) ? date('01-') . Yii::$app->request->get('TblStaffSalaryProcess')['month'] : '';
        $searchModel->month = !empty($month) ? date('Y-m-d', strtotime($month)) : NULL;
        $dataProvider = $searchModel->search([]);
        $this->model = new TblStaffSalaryProcess();
        $this->model->union_code = !empty(Yii::$app->request->get('TblStaffSalaryProcess')['union_code']) ? Yii::$app->request->get('TblStaffSalaryProcess')['union_code'] : NULL;
        $this->model->month = !empty(Yii::$app->request->get('TblStaffSalaryProcess')['month']) ? Yii::$app->request->get('TblStaffSalaryProcess')['month'] : '';
        return $this->renderAjax('_form', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $this->model,]);
    }

}
