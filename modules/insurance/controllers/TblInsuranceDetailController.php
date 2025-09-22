<?php

namespace app\modules\insurance\controllers;

use app\controllers\ChildController;
use app\modules\dcsoperation\models\TblMember;
use Yii;
use app\modules\insurance\models\TblInsuranceDetail;
use app\modules\insurance\models\TblInsuranceDetailHistory;
use app\modules\insurance\models\TblInsuranceDetailSearch;
use yii\web\NotFoundHttpException;
use app\modules\insurance\models\TblInsuranceMaster;
use app\modules\organisation\models\TblDcs;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\insurance\models\TblInsuranceDetailSummary;
use app\modules\insurance\models\TblInsuranceDetailSummaryHistory;
use app\modules\syncutility\models\TblGenerateSentbox;
use yii\base\UserException;
use yii\helpers\Url;
use yii\db\Expression;
use DateTime;
use Exception;

/**
 * TblInsuranceDetailController implements the CRUD actions for TblInsuranceDetail model.
 */
class TblInsuranceDetailController extends ChildController {

    public $freeAccessActions = ['get-member-name'];

    /**
     * Lists all TblInsuranceDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblInsuranceDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMemberIndex($header_detail) {
        $searchModel = new TblInsuranceDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->grid_filter = FALSE;
        return $this->render('member_details', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'header_detail' => $header_detail
        ]);
    }

    /**
     * Displays a single TblInsuranceDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblInsuranceDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblInsuranceDetail();
        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $insuranceMaster = TblInsuranceMaster::find()->where(['insurance_master_code' => $this->model->insurance_master_code])->one();
            $this->setModel($this->model);
            $DCSEditEndDate = $this->model->getdcsEditEndDate($this->model->insurance_master_code, $this->model->dcs_code);
            if (!empty($insuranceMaster) && strtolower($insuranceMaster->status) == 'draft' && $DCSEditEndDate) {
                if (empty($this->model->getErrors()) && $this->model->validate()) {
                    $this->model->insurance_detail_code = Yii::$app->general->getCodeMax($this->model);
                    $this->model->x_col1 = Yii::$app->general->getUuid();
                    $master = [];
                    $insuranceSummary = $this->model->checkInsuranceDetail($this->model->insurance_master_code, $this->model->dcs_code, ['PUBLISH', 'DRAFT']);
                    if (empty($insuranceSummary)) {
                        if (!empty($insuranceMaster)) {
                            $summaryModel = new TblInsuranceDetailSummary();
                            $summaryModel->attributes = $this->model->attributes;
                            $master[] = $summaryModel;
                        }
                    }
                    $master[] = $this->model;
                    $transaction = $this->generalModel->saveTransaction($master, ['Insurance Detail', 'create']);
                    if ($transaction !== FALSE) {
                        return $this->{$transaction}();
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Cannot add record. Insurance must be in draft status and within the allowed DCS edit period.']);
                return $this->redirect(['create']);
            }
        }
        return $this->customRender();
    }

    public function actionImportInsuranceDetail() {
        $path = Yii::$app->basePath . '/web/import/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = 'excel_' . time() . '.' . $file->extension;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    /**
     * Updates an existing TblInsuranceDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->viewFile = 'update';
        $this->model = $this->findModel($id);
        $this->model->scenario = 'update';
        if (Yii::$app->request->post()) {
            $DCSEditEndDate = $this->model->getdcsEditEndDate($this->model->insurance_master_code, $this->model->dcs_code);
            if ($DCSEditEndDate) {
                $master = [];
                if (strtolower($this->model->status) == 'publish' || strtolower($this->model->status) == 'partial_finalize') {
                    $historyModel = new TblInsuranceDetailHistory();
                    Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                    $this->model->sys_updated_by = 'PORTAL';
                    $master[] = $historyModel;
                }
                $this->model->load(Yii::$app->request->post());
                $this->setModel($this->model);
                if (empty($this->model->getErrors()) && $this->model->validate()) {
                    $master[] = $this->model;
                    $transaction = $this->generalModel->saveTransaction($master, ['Insurance Detail', 'edit']);
                    if ($transaction !== FALSE) {
                        return $this->{$transaction}();
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'You Can Not Edit This Record.']);
                return $this->customRender();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblInsuranceDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        $save_list = [];
        $today = date("Y-m-d");
        $insuranceDetailSummaryModel = new TblInsuranceDetailSummary();
        $existSummaryModel = $insuranceDetailSummaryModel->getInsuranceDetailSummary($this->model->insurance_master_code, $this->model->dcs_code);
        $record = [];

        if (strtolower($this->model->status) == 'draft') {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $summaryCode = $this->model->getInsuranceDetailCode();
                if (empty($summaryCode)) {
                    $existSummaryModel->delete();
                }
                $this->model->delete();
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
            } catch (UserException $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => $e->getMessage()];
            } catch (\yii\db\Exception $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => htmlspecialchars($e, ENT_QUOTES, 'UTF-8')];
            }
        } else if ($this->model->status == 'PUBLISH' && strtotime($today) >= strtotime($existSummaryModel->to_date) && ($today) <= strtotime($existSummaryModel->from_date)) {
            $historyModel = new TblInsuranceDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $save_list[] = $this->model;
            $save_list[] = $historyModel;
            $summaryCode = $this->model->getInsuranceDetailCode();
            if (empty($summaryCode)) {
                $insuranceDetailSummaryModel = new TblInsuranceDetailSummary();
                $existSummaryModel = $insuranceDetailSummaryModel->getInsuranceDetailSummary($this->model->insurance_master_code, $this->model->dcs_code);
                $historyModel = new TblInsuranceDetailSummaryHistory();
                Yii::$app->operation->history($existSummaryModel, $historyModel, DELETE);
                $save_list[] = $existSummaryModel;
                $save_list[] = $historyModel;
            }
            $record = $this->generalModel->deleteTransaction($save_list);
        } else {
            $record = ['status' => 'error', 'msg' => 'Cannot delete a record that is in finalize status.'];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode($record);
    }

    /**
     * Finds the TblInsuranceDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblInsuranceDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblInsuranceDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function setModel($model) {
        $today = date("Y-m-d");
        $getInsuranceDetail = TblInsuranceDetail::find()->where(['status' => ['DRAFT', 'PUBLISH', 'PARTIAL_FINALIZE'], 'dcs_code' => $model->dcs_code])->orderBy(['member_id' => SORT_DESC])->one();
        $dcsDetail = TblDcs::find()->where(['dcs_code' => $model->dcs_code])->one();
        $insuranceSummary = $model->checkInsuranceDetail($model->insurance_master_code, $model->dcs_code, ['PUBLISH', 'FINALIZE', 'PARTIAL_FINALIZE']);

        if (!empty($insuranceSummary) && $insuranceSummary->status == 'FINALIZE' || (!empty($insuranceSummary) && strtotime($insuranceSummary->to_date) >= strtotime($today) && $insuranceSummary->status != 'PARTIAL_FINALIZE')) {
            $model->addError('insurance_master_code', ($insuranceSummary->status == 'FINALIZE') ? 'You cannot create/update this record because it has been finalized.' : 'You cannot create/update because this is assigned to the desktop level');
            return false;
        }
        $model->status = !empty($insuranceSummary) ? $insuranceSummary->status : 'DRAFT';
        $model->dcs_name = $dcsDetail->dcs_name;
        if (!empty($getInsuranceDetail)) {
            $model->member_id = empty($model->insurance_detail_code) ? $getInsuranceDetail->member_id + 1 : $model->member_id;
        } else {
            $model->member_id = date('y') . $dcsDetail->dcs_code_ex . '0001';
            // $model->member_id = empty($model->insurance_detail_code) ? date('y').$dcsDetail->dcs_code_ex.'0001' : $model->member_id;
        }
        if (!empty($model->member_id)) {
            $model->sr_no = str_pad(substr($model->member_id, 6, 4), 4, "0", STR_PAD_LEFT);
        }
        $model->date_of_joining_scheme = !empty($model->date_of_joining_scheme) ? Yii::$app->controls->view_date($model->date_of_joining_scheme, 'php:Y-m-d') : NULL;
        $model->dob = !empty($model->dob) ? Yii::$app->controls->view_date($model->dob, 'php:Y-m-d') : NULL;
        $diff = date_diff(date_create($model->dob), date_create($today));
        $model->age = $diff->format('%y');
    }

    public function actionPublishFinalize() {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $fromDate = !empty($postData['from_date']) ? $postData['from_date'] : '';
            $toDate = !empty($postData['to_date']) ? $postData['to_date'] : '';
            $today = date("Y-m-d");
            $status = strtoupper($postData['process_flag']);
            if ($status == 'PUBLISH') {
                if (strtotime($toDate) < strtotime($fromDate) || $fromDate == null || $toDate == null || strtotime($fromDate) < strtotime($today)) {
                    $message = 'To date must be greater or equal than From Date';
                    if ($fromDate == null || $toDate == null) {
                        $message = 'From Date or To date Cannot Be Blank';
                    } elseif (strtotime($fromDate) < strtotime($today)) {
                        $message = 'From date Must Be Greater Than Today Date';
                    }
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $message]);
                    return $this->redirect(['publish-finalize']);
                }
            }
            $summeryModel = new TblInsuranceDetailSummary();
            $summeryModel = $summeryModel->getPublishFinalizeData($postData['insurance_master_code'], $status);
            $is_dcs_editable = TRUE;
            if (!empty($summeryModel)) {
                $master = [];
                $dcsCode = [];
                foreach ($summeryModel as $value) {
                    $value->operation = true;
                    $historyModel = new TblInsuranceDetailSummaryHistory();
                    Yii::$app->operation->history($value, $historyModel, UPDATE);
                    $value->status = $status;
                    $value->scenario = 'publish_finalize_summary';
                    if ($status == 'PUBLISH') {
                        $value->from_date = ($fromDate == '') ? null : Yii::$app->formatter->asDate($fromDate, DATE_FORMAT);
                        $value->to_date = ($toDate == '') ? null : Yii::$app->formatter->asDate($toDate, DATE_FORMAT);
                        $genSentBox = new TblGenerateSentbox();
                        $genSentBox->table_name = 'tbl_insurance_detail';
                        $genSentBox->where_clause = "dcs_code='$value->dcs_code'";
                        $genSentBox->operation_type = 'INSERT';
                        $genSentBox->sentbox_key = 'dcs_code';
                        $genSentBox->union_code = $value->union_code;
                        $genSentBox->plant_code = $value->plant_code;
                        $genSentBox->mcc_plant_code = $value->mcc_plant_code;
                        $genSentBox->bmc_code = $value->bmc_code;
                        $genSentBox->dcs_code = $value->dcs_code;
                        $genSentBox->status = 0;
                        $genSentBox->dest_org_type = 'VLC';
                        $master[] = $genSentBox;
                    }
                    $master[] = $value;
                    $master[] = $historyModel;
                    if ($status == 'FINALIZE') {
                        if (strtotime($today) <= strtotime($value->to_date)) {
                            $dcsCode[] = $value->dcs_code . ' - ' . $value->dcs_name;
                            $is_dcs_editable = FALSE;
                        }
                    }
                }
                if ($is_dcs_editable) {
                    $insuranceMaster = TblInsuranceMaster::find()->where(['insurance_master_code' => $postData['insurance_master_code']])->one();
                    $insuranceMaster->operation = true;
                    $insuranceMaster->status = $status;
                    $master[] = $insuranceMaster;
                    $transaction = $this->generalModel->saveTransaction($master, ['Insurance Detail ' . $status, 'edit']);
                    if ($transaction == 'customRedirect') {
                        $detailModel = new TblInsuranceDetail();
                        $detailModel->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'status' => $status], ['insurance_master_code' => $postData['insurance_master_code']]);
                        return $this->redirect(['publish-finalize']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'You cannot finalize the data because the following Society are editable: <br>' . implode('<br>', $dcsCode)]);
                    return $this->redirect(['publish-finalize']);
                }
            }
        }
        $searchModel = new TblInsuranceDetailSearch();
        $dataProvider = $searchModel->searchSummary(Yii::$app->request->queryParams);
        return $this->render('publish_finalize', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateInsuranceDetail() {

        $model = new TblInsuranceDetail();

        $model->scenario = 'import_insurance_detail';

        if ($model->load(Yii::$app->request->post())) {
            $message = '';
            $masterModel = [];
            $childModel = [];
            $insurance_master_code = $_POST['TblInsuranceDetail']['insurance_master_code'];
            $model->insurance_master_code = $insurance_master_code;
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->validate()) {
                $insuranceMaster = TblInsuranceMaster::find()->where(['insurance_master_code' => $insurance_master_code])->one();
                TblInsuranceDetail::deleteAll(['insurance_master_code' => $insurance_master_code]);
                TblInsuranceDetailSummary::deleteAll(['insurance_master_code' => $insurance_master_code]);
                $this->uploadExcel($_POST['file_name'], $masterModel, $childModel, $insuranceMaster, $message);
                if ($message == '') {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $master = [];
                        foreach ($masterModel as $d) {
                            \Yii::$app->db->createCommand()->batchInsert('tbl_insurance_detail', array_keys($d[0]), $d)->execute();
                        }
                        \Yii::$app->db->createCommand()->batchInsert('tbl_insurance_detail_summary', array_keys($childModel[0]), $childModel)->execute();
                        if ($transaction->isActive) {
                            $transaction->commit();
                            $url = Url::to(['index']);
                            $importPath = Yii::$app->basePath . '/web/import/';
                            if (!empty($_POST['file_name'])) {
                                unlink($importPath . $_POST['file_name']);
                            }
                            Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => 'Insurance file imported successfully']);
                            return ['status' => 'success', 'url' => $url, 'message' => 'Your transaction is not saved successfully'];
                        }
                        Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Your transaction is not saved successfully']);
                        return ['status' => 'error', 'message' => 'Data not Saved Due to transaction Error'];
                    } catch (Exception $e) {
                        $transaction->rollback();
                        return ['status' => 'error', 'message' => 'Your transaction is not saved successfully'];
                    }
                } else {
                    return ['status' => 'error', 'message' => $message];
                }
            } else {
                return ActiveForm::validate($model);
            }
        }

        return $this->render('create_insurance_detail', [
                    'model' => $model,
        ]);
    }

    public function actionCheckDataExists() {
        $postData = Yii::$app->request->post('TblInsuranceDetail');
        if ($postData && isset($postData['insurance_master_code'])) {
            $insurance_master_code = $postData['insurance_master_code'];
            $detailData = TblInsuranceDetail::find()->where(['insurance_master_code' => $insurance_master_code])->one();
            $type = '';
            $msg = '';
            $status = 'success';
            if (!empty($detailData)) {
                $status = 'error';
                if (strtolower($detailData->status) == 'draft') {
                    $msg = 'Are you sure you want to regenerate insurance Data ?';
                    $type = 'draft';
                } else {
                    $msg = 'You cannot import the record because it is in ' . $detailData->status . ' status.';
                    $type = 'publishOrFinilize';
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => $status, 'msg' => $msg, 'type' => $type];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => 'error', 'msg' => 'Missing or invalid Insurance Master Code in request data.', 'type' => 'error'];
        }
    }

    private function uploadExcel($fileName, &$masterModel, &$childModel, $insuranceMaster, &$message, $oneDcsCode = 'FALSE') {
        $importPath = Yii::$app->basePath . '/web/import/';
        $objPHPExcel = \PHPExcel_IOFactory::load($importPath . $fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

            $column_one = $worksheet->getCell('A1')->getValue();
            $column_two = $worksheet->getCell('B1')->getValue();
            $column_three = $worksheet->getCell('C1')->getValue();
            $column_four = $worksheet->getCell('D1')->getValue();
            $column_five = $worksheet->getCell('E1')->getValue();
            $column_six = $worksheet->getCell('F1')->getValue();
            $column_seven = $worksheet->getCell('G1')->getValue();
            $column_eight = $worksheet->getCell('H1')->getValue();
            $column_nine = $worksheet->getCell('I1')->getValue();
            $column_ten = $worksheet->getCell('J1')->getValue();
            $column_eleven = $worksheet->getCell('K1')->getValue();
            $column_twelve = $worksheet->getCell('L1')->getValue();
            $column_thirteen = $worksheet->getCell('M1')->getValue();

            if ($column_one == 'Sr No' && $column_two == 'Society Code' && $column_three == 'Society Name' && $column_four == 'Member Id' && $column_five == 'Adhar No' && $column_six == 'Member Code' && $column_seven == 'Member Name' && $column_eight == 'Gender Code' && $column_nine == 'Dob' && $column_ten == 'Age' && $column_eleven == 'Nominee Member Name' && $column_twelve == 'Date Of Joining Scheme' && $column_thirteen == 'Nominee Adhar No') {
                $dcsCodes = [];
                $detailModel = new TblInsuranceDetail();
                $insurance_detail_code = Yii::$app->general->getCodeMax($detailModel);
                $insuranceMinMaxAge = $detailModel->getInsuranceMaster($insuranceMaster->insurance_master_code);
                $memberMinAge = !empty($insuranceMinMaxAge->member_min_age) ? $insuranceMinMaxAge->member_min_age : '';
                $memberMaxAge = !empty($insuranceMinMaxAge->member_max_age) ? $insuranceMinMaxAge->member_max_age : '';
                $organizations_code = Yii::$app->session->get('organizations_code');
                $lastNumber = (int) substr($insurance_detail_code, strlen("PORTAL-" . $organizations_code . "-"));
                $orgCode = 'PORTAL-' . $organizations_code . '-';
                $dcsCodesQuery = [];
                $sheetAdharNos = [];
                $sheetMemberCodes = [];
                $sheetMemberIds = [];
                $i = 0;
                $data = [];
                if ($oneDcsCode != 'FALSE') {
                    $adharNoArray = TblInsuranceDetail::find()
                            ->select(['adhar_no_data' => new Expression("adhar_no")])
                            ->where(['insurance_master_code' => $insuranceMaster->insurance_master_code])
                            ->andWhere(['not in', 'dcs_code', $oneDcsCode])
                            ->indexBy('adhar_no_data')
                            ->asArray()
                            ->all();
                    $oneDcsCode = TblDcs::find()->select('dcs_code_ex')->where(['is_active' => 1])->andWhere(['dcs_code' => $oneDcsCode])->one();
                }
                for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
                    $dcsCode = $worksheet->getCell('B' . $row)->getValue();
                    $dcsCode = str_pad($dcsCode, 4, '0', STR_PAD_LEFT);
                    if (!empty($dcsCode) && !in_array($dcsCode, $dcsCodesQuery)) {
                        $dcsCodesQuery[] = $dcsCode;
                    }
                }
                $dcsArray = TblDcs::find()
                        ->select([
                            'dcs_data' => new Expression("dcs_code_ex"),
                            'org_data' => new Expression("union_code + '###' + plant_code + '###' + bmc_code + '###' + mcc_plant_code + '###' + dcs_code")
                        ])
                        ->where(['is_active' => 1])
                        ->andWhere(['dcs_code_ex' => $dcsCodesQuery])
                        ->indexBy(function($row) {
                            return $row['dcs_data'];
                        })
                        ->asArray()
                        ->all();

                for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
                    $insuranceDetailModel = new TblInsuranceDetail();
                    $dcsCode = (int) $worksheet->getCell('B' . $row)->getValue();
                    $insuranceDetailModel->insurance_detail_code = $orgCode . $lastNumber;
                    $insuranceDetailModel->insurance_master_code = $insuranceMaster->insurance_master_code;
                    $dcsmemberCode = $worksheet->getCell('B' . $row)->getValue() . $worksheet->getCell('F' . $row)->getValue();
                    $srNo = $worksheet->getCell('D' . $row)->getValue();
                    $adharNo = str_replace(' ', '', $worksheet->getCell('E' . $row)->getValue());
                    // $adharNo = preg_replace('/[^A-Za-z0-9\-]/', '', $adharNo);
                    $dob = $worksheet->getCell('I' . $row)->getValue();
                    $insuranceDetailModel->sr_no = str_pad(substr($srNo, 6, 4), 4, "0", STR_PAD_LEFT);
                    $insuranceDetailModel->dcs_code = $worksheet->getCell('B' . $row)->getValue();
                    $insuranceDetailModel->dcs_name = $worksheet->getCell('C' . $row)->getValue();
                    $insuranceDetailModel->member_id = $worksheet->getCell('D' . $row)->getValue();
                    $memberCode = $worksheet->getCell('F' . $row)->getValue();
                    $insuranceDetailModel->member_name = $worksheet->getCell('G' . $row)->getValue();
                    $insuranceDetailModel->gender_code = $worksheet->getCell('H' . $row)->getValue();
                    $insuranceDetailModel->age = $worksheet->getCell('J' . $row)->getValue();
                    $insuranceDetailModel->nominee_member_name = $worksheet->getCell('K' . $row)->getValue();
                    $insuranceDetailModel->date_of_joining_scheme = $worksheet->getCell('L' . $row)->getValue();
                    $nomineeAdharNo = $worksheet->getCell('M' . $row)->getValue();
                    $insuranceDetailModel->x_col1 = Yii::$app->general->getUuid();
                    $insuranceDetailModel->status = $insuranceMaster->status;
                    $insuranceDetailModel->is_delete = 0;
                    $insuranceDetailModel->created_by = \Yii::$app->user->identity->user_code;
                    $insuranceDetailModel->created_at = date('Y-m-d H:i:s');
                    $insuranceDetailModel->originating_org_code = \Yii::$app->session->get('organizations_code');
                    $insuranceDetailModel->originating_org_type = 'PORTAL';
                    $insuranceDetailModel->originating_type = 0;

                    $errors = [];

                    if ($oneDcsCode != 'FALSE' && !empty($oneDcsCode) && $oneDcsCode->dcs_code_ex != $dcsCode) {
                        $message = 'Please Enter Selected Sociey Code In Sheet.';
                        $message = 'There is error in Record No : ' . ($row - 1) . '<br>' . $message;
                        break;
                    }

                    if (in_array($insuranceDetailModel->member_id, $sheetMemberIds)) {
                        $errors[] = 'Member id already exist in sheet.';
                    }
                    $sheetMemberIds[] = $insuranceDetailModel->member_id;
                    // if (in_array($dcsmemberCode, $sheetMemberCodes)) {
                    //     $errors[] = 'Member code already exist in sheet.';
                    // }
                    if (!preg_match('/^0+$/', $memberCode)) {
                        $sheetMemberCodes[] = $dcsmemberCode;
                    }
                    // if (in_array($adharNo, $sheetAdharNos)) {
                    //     $errors[] = 'Adhar no already exist in sheet.';
                    // }
                    $sheetAdharNos[] = $adharNo;

                    if (!empty($dcsCode) && isset($memberCode)) {
                        $memberCode = str_pad($memberCode, 4, '0', STR_PAD_LEFT);
                        $dcsCode = str_pad($dcsCode, 4, '0', STR_PAD_LEFT);
                        if (!preg_match('/^\d{4}$/', $dcsCode)) {
                            $errors[] = 'Society Code must be 4 digits long.';
                        }
                        else if (array_key_exists($dcsCode, $dcsArray)) {
                            $orgData = $dcsArray[$dcsCode]['org_data'];
                            $orgDataParts = explode('###', $orgData);
                            $insuranceDetailModel->union_code = $orgDataParts[0];
                            $insuranceDetailModel->plant_code = $orgDataParts[1];
                            $insuranceDetailModel->bmc_code = $orgDataParts[2];
                            $insuranceDetailModel->mcc_plant_code = $orgDataParts[3];
                            $insuranceDetailModel->dcs_code = $orgDataParts[4];
                            $insuranceDetailModel->member_code = $orgDataParts[4] . $memberCode;
                        } else {
                            $errors[] = 'Invalid Society Code';
                        }
                    } else {
                        if (empty($dcsCode)) {
                            $errors[] = 'Society Code cannot be blank.';
                        }
                        if (empty($memberCode)) {
                            $errors[] = 'Member Code cannot be blank.';
                        }
                    }

                    if (empty($insuranceDetailModel->dcs_name)) {
                        $errors[] = 'Society Name cannot be blank';
                    }

                    if (empty($insuranceDetailModel->member_name)) {
                        $errors[] = 'Member Name cannot be blank';
                    }

                    if (empty($insuranceDetailModel->member_id)) {
                        $errors[] = 'Member Id cannot be blank.';
                    } elseif (!ctype_digit((string)$insuranceDetailModel->member_id)) {
                        $errors[] = 'Member Id must be numeric.';
                    } elseif (!preg_match('/^\d{10}$/', $insuranceDetailModel->member_id)) {
                        $errors[] = 'Member Id must be 10 digits long.';
                    } else {
                        $memberId = $insuranceDetailModel->member_id;
                        $societyCode = substr($memberId, 2, 4);
                        if ($dcsCode != $societyCode) {
                            $errors[] = 'Digits 3 to 6 of the Member ID must be a valid Society Code.';
                        }
                    }

                    if (empty($adharNo)) {
                        $errors[] = 'Aadhar Number cannot be blank.';
                    } else if(!empty($adharNo)){
                        $insuranceDetailModel->adhar_no = \Yii::$app->general->encryptData($adharNo);
                    }

                    // if (empty($adharNo)) {
                    //     $errors[] = 'Aadhar Number cannot be blank.';
                    // } elseif (!preg_match('/^[0-9]{12}$/', $adharNo)) {
                    //     $errors[] = 'Aadhar card number can only contain exactly 12 digits.';
                    // } else {
                    //     $encryptedAdharNo = \Yii::$app->general->encryptData($adharNo);
                    //     if ($oneDcsCode != 'FALSE' && array_key_exists($encryptedAdharNo, $adharNoArray)) {
                    //         $errors[] = 'Please enter a unique Aadhar No.';
                    //     } else {
                    //         $insuranceDetailModel->adhar_no = $encryptedAdharNo;
                    //     }
                    // }

                    // if (!empty($nomineeAdharNo) && !preg_match('/^[0-9]{12}$/', $nomineeAdharNo)) {
                    //     $errors[] = 'Nominee Aadhar card number can only contain exactly 12 digits.';
                    // }

                    if (empty($dob)) {
                        $errors[] = 'DOB cannot be blank.';
                    } else {
                        $dateDotFormat = DateTime::createFromFormat('d.m.Y', $dob);
                        if ($dateDotFormat) {
                            $dob = $dateDotFormat->format('Y-m-d');
                        } else {
                            $errors[] = 'Please enter DOB in a valid format, e.g. 01.12.2018.';
                        }
                    }

                    $validGenders = ['MALE' => 1, 'M' => 1, 'FEMALE' => 2, 'F' => 2, 'TRANSGENDER' => 3, 'T' => 3];
                    if (empty($insuranceDetailModel->gender_code)) {
                        $errors[] = 'Gender Code cannot be blank.';
                    } elseif (!array_key_exists(strtoupper($insuranceDetailModel->gender_code), $validGenders)) {
                        $errors[] = 'Invalid gender code.';
                    } else {
                        $insuranceDetailModel->gender_code = $validGenders[strtoupper($insuranceDetailModel->gender_code)];
                    }

                    if (empty($insuranceDetailModel->date_of_joining_scheme)) {
                        $errors[] = 'Date Of Joining Scheme cannot be blank.';
                    } else {
                        $dateDotFormat = DateTime::createFromFormat('d.m.Y', $insuranceDetailModel->date_of_joining_scheme);
                        $dateDashFormat = DateTime::createFromFormat('d-m-Y', $insuranceDetailModel->date_of_joining_scheme);
                        if ($dateDotFormat || $dateDashFormat) {
                            $insuranceDetailModel->date_of_joining_scheme = $dateDotFormat ? $dateDotFormat->format('Y-m-d') : $dateDashFormat->format('Y-m-d');
                        } else {
                            $errors[] = 'Please enter Date Of Joining Scheme in a valid format, e.g., 01.12.2018 or 01-12-2018.';
                        }
                    }

                    if (!empty($errors)) {
                        $message .= implode('<br>', $errors);
                        $message = 'There is error in Record No : ' . ($row - 1) . '<br>' . $message;
                        break;
                    } else {
                        $insuranceDetailModel->adhar_no = \Yii::$app->general->encryptData($adharNo);
                        $insuranceDetailModel->dob = \Yii::$app->general->encryptData($dob);
                        $insuranceDetailModel->nominee_adhar_no = !empty($nomineeAdharNo) ? \Yii::$app->general->encryptData($nomineeAdharNo) : NULL;

                        $data[$i][] = $insuranceDetailModel->attributes;
                        if (count($data [$i]) == 1000) {
                            $i ++;
                        }
                        $lastNumber++;
                        if (!in_array($insuranceDetailModel->dcs_code, $dcsCodes)) {
                            $dcsCodes[] = $insuranceDetailModel->dcs_code;
                            $insuranceDetailSummaryModel = new TblInsuranceDetailSummary();
                            $insuranceDetailSummaryModel->attributes = $insuranceDetailModel->attributes;
                            $attributes = $insuranceDetailSummaryModel->attributes;
                            unset($attributes['insurance_detail_summary_code']);
                            $childModel[] = $attributes;
                        }
                    }
                    if ($oneDcsCode != 'FALSE') {
                        $masterModel[] = $insuranceDetailModel;
                    }
                }
                if ($oneDcsCode == 'FALSE') {
                    $masterModel = $data;
                }
            } else {
                $message .= 'Invalid Sheet Format. ';
            }
            if ((int) $worksheet->getHighestRow() <= 1) {
                $message .= 'Please Upload Valid Excel Sheet.';
            }
        }
    }

    public function actionGetMemberName() {
        $member_code = Yii::$app->request->post('member_code');
        $type = Yii::$app->request->post('type');
        $member_id = Yii::$app->request->post('member_id');
        $data = ($type == 'create') ? (new TblMember())->validMember($member_code) : (new TblInsuranceDetail())->validInsuranceDetailMember($member_code, $member_id);
        return !empty($data) ? json_encode(['status' => 'success', 'member_name' => $data->member_name]) : json_encode(['status' => 'error']);
    }

    public function actionDcsWiseImport() {
        $model = new TblInsuranceDetail(['scenario' => 'dcs_wise_import']);
        if ($model->load(Yii::$app->request->post())) {
            $message = '';
            $masterModel = $childModel = $deleteModel = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->validate()) {
                $insuranceMaster = TblInsuranceMaster::find()->where(['insurance_master_code' => $model->insurance_master_code])->one();
                $this->uploadExcel($_POST['file_name'], $masterModel, $childModel, $insuranceMaster, $message, $model->dcs_code);
                if ($message == '') {
                    $deletedetail = $model::find()->where(['insurance_master_code' => $model->insurance_master_code, 'dcs_code' => $model->dcs_code, 'status' => 'PUBLISH'])->all();
                    foreach ($deletedetail as $value) {
                        $historyModel = new TblInsuranceDetailHistory();
                        Yii::$app->operation->history($value, $historyModel, DELETE);
                        $deletedata[] = $value;
                        $masterModel[] = $historyModel;
                    }
                    $transaction = $this->generalModel->saveDeleteTransaction($masterModel, [], $deletedata, ['Insurance Detail', 'create']);
                    if ($transaction == 'customRedirect') {
                        $importPath = Yii::$app->basePath . '/web/import/';
                        if (!empty($_POST['file_name'])) {
                            unlink($importPath . $_POST['file_name']);
                        }
                        return $this->redirect(['index']);
                    }
                } else {
                    return ['status' => 'error', 'message' => $message];
                }
            } else {
                return ActiveForm::validate($model);
            }
        }
        return $this->render('dcs_wise_import', ['model' => $model]);
    }

    public function actionCheckDataExistsDcsWise() {
        if (Yii::$app->request->post('TblInsuranceDetail')) {
            $status = 'success';
            $msg = 'Are you sure you want to regenerate Selected Society insurance Data ?';
        } else {
            $status = 'error';
            $msg = 'Missing or invalid Insurance Master Code in request data.';
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['status' => $status, 'msg' => $msg];
    }

}