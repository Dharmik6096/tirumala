<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblTankerRate;
use app\modules\tankermovement\models\TblTankerRateSearch;
use app\modules\tankermovement\models\TblTankerRateDetails;
use app\modules\dcsoperation\models\TblRateType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblQualityParam;
use app\modules\tankermovement\models\TblTankerRateBased;
use ReflectionClass;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use PHPExcel_Cell;
use yii\helpers\ArrayHelper;
use app\modules\tankermovement\models\TblTankerRateApplicabilitySearch;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\tankermovement\models\TblTankerRateApplicability;
use app\modules\tankermovement\models\TblTankerRateApplicabilityHistory;
use yii\helpers\Json;
use PHPExcel;
use app\modules\tankermovement\models\TblTankerRateHistory;

/**
 * TblTankerRateController implements the CRUD actions for TblTankerRate model.
 */
class TblTankerRateController extends \app\controllers\ChildController {

    public $purchaseModel;
    public $freeAccessActions = ['chart-list', 'get-org-rate', 'dpu-chart-list'];

    /**
     * Lists all TblTankerRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTankerRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->purchaseModel = new TblTankerRateBased();
        if (isset(Yii::$app->request->post()['templet-download'])) {
            $transaction = Json::decode($_POST['range_table']);
            if (count($transaction) > 0) {
                $objPHPExcel = new PHPExcel();
                $sheetcnt = 0;
            }
            for ($i = 0; $i < count($transaction); $i++) {
                $milk_type = $transaction[$i]['milk_type'];
                $rate_type = $transaction[$i]['rate_type_code'];
                $milk_quality_type = $transaction[$i]['rate_type_code'];

                $rowCount = 1;
                $column = 'A';
                if ($sheetcnt == 0) {
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->setTitle($milk_type);
                } else {
                    $sheet = $objPHPExcel->createSheet();
                    $sheet->setTitle($milk_type);
                }
                $sheet->setCellValue($column . $rowCount, $rate_type);
                $column++;
                $cnt = 0;
                if ($snf_start == 0) {
                    for (; $fat_start <= $fat_end;
                    ) {
                        if ($fat_start == $transaction[$i]['fat_start']) {
                            $sheet->setCellValue($column . $rowCount, 'RTPL');
                        }
                        //else {
                        $rowCount++;
                        $column = 'A';
                        $sheet->setCellValue($column . $rowCount, $fat_start);
                        //  }
                        $fat_start = floatval(bcadd($fat_start, 0.1, 1));
                    }
                } else {
                    for (; $fat_start <= $fat_end;
                    ) {
                        if ($fat_start == $transaction[$i]['fat_start']) {
                            for (; $snf_start <= $snf_end;
                            ) {
                                $sheet->setCellValue($column . $rowCount, $snf_start);
                                $column++;
                                $snf_start = floatval(bcadd($snf_start, 0.1, 1));
                            }
                        }
                        //else {
                        $rowCount++;
                        $column = 'A';
                        $sheet->setCellValue($column . $rowCount, $fat_start);
                        $column++;
                        // }
                        $fat_start = floatval(bcadd($fat_start, 0.1, 1));
                    }
                }
                $sheetcnt++;
            }
            if (count($transaction) > 0) {
                $header = [
                    'mime' => 'application/vnd.ms-excel',
                    'extension' => 'xls',
                    'writer' => 'Excel5',
                ];
                $fileName = "RateChart." . $header['extension'];
                header('Content-Type: ' . $header['mime']);
                header('Content-Disposition: attachment;filename=' . $fileName);
                header('Cache-Control: max-age=0');
                $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, $header['writer']);
                ob_end_clean();
                $objWriter->save('php://output');
                // exit();
                //return $this->redirect(['index']);
            }
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'purchaseBasedModel' => $this->purchaseModel
        ]);
    }

    /**
     * Displays a single TblTankerRate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $purchaseBasedModel = new TblTankerRateBased();
        $purchaseBasedModel->tanker_rate_code = $id;
        $appsearchModel = new TblTankerRateApplicabilitySearch();
        $appsearchModel->tanker_rate_code = $id;
        $appdataProvider = $appsearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'purchaseBasedModel' => $purchaseBasedModel, 'appsearchModel' => $appsearchModel,
                    'appdataProvider' => $appdataProvider,
        ]);
    }

    /**
     * Creates a new TblTankerRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblTankerRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date) ? Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT) : '';
            $this->model->wef_date = $this->model->wef_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $this->model->rate_for = isset($this->model->rate_for) ? $this->model->rate_for : '';
            $this->model->tanker_rate_code = $this->model->getCode();
            $this->model->is_active = 1;
            $this->model->originating_org_code = Yii::$app->session->get('organizations_code');
            $this->model->originating_org_type = Yii::$app->session->get('organizations_type');
            //$this->model->scenario = 'create';
            if ($this->model->validate()) {
                if ($this->model->rate_gen_method_code == 3) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $this->uploadExcel($_POST['file_name'], $this->model);
                }
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['tbl-tanker-rate-details/create-rate', 'id' => -1, 'method' => $this->model->rate_gen_method_code]);
                return ['status' => $result, 'url' => $url, 'originating_org_type' => $this->model->originating_org_type, 'originating_org_code' => $this->model->originating_org_code, 'rate_method' => $this->model->rate_gen_method_code, 'rate_for' => $this->model->rate_for, 'wef_date' => $this->model->wef_date, 'description' => $this->model->description, 'shift_code' => $this->model->shift_code, 'union_code' => $this->model->union_code];
            } else {
                $file = [];
                if ($_POST['file_name'] != '')
                    explode(',', $_POST['file_name']);

                foreach ($file as $f) {
                    unlink(IMPORT_PATH . $f);
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->model);
            }
        }

        return $this->customRender();
    }

    public function uploadExcel($fileName, $purchaseRate) {

        $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load(IMPORT_PATH . $fileName);
        $data = [];
        $rateTypeModel = new TblRateType();
        $milkTypeModel = new TblAnimalType();
        $i = 0;
        $cnt = 0;
        $purchaseModel = new TblTankerRateDetails();
        $based = [];
        $baseCode = 0;
        $error = FALSE;
        $errorarray = [];
        $ratearray = [];

        $animalTypedata = $milkTypeModel->getRecords();
        $animalTypedata = ArrayHelper::getColumn($animalTypedata, 'oldAttributes');
        $SheetNames = ArrayHelper::getColumn($animalTypedata, 'animal_type_name');
        $animalTypedata = \yii\helpers\ArrayHelper::map($animalTypedata, 'animal_type_code', 'animal_type_name');
        $milkTypedata = array("1" => "FAT+SNF", "2" => "QTY");
        $RateTypes = array("FAT+SNF", "QTY");
        $milkTypedata = \yii\helpers\ArrayHelper::map($milkTypedata, 'code', 'rate_type');
        $milkQuality = new TblMilkQualityType();
        $QualityType = array_values($milkQuality->getActiveQualityType());

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $sheetTitle = strtolower($worksheet->getTitle());
            $sheetTitlearray = explode('-', $sheetTitle);
            if (count($sheetTitlearray) == 2 && in_array($sheetTitlearray[0], array_map('strtolower', $SheetNames)) && in_array($sheetTitlearray[1], array_map('strtolower', $QualityType))) {
                $FormulaType = strtoupper($worksheet->getCell('A1')->getValue());
                if (!isset($ratearray[$sheetTitlearray[0]])) {
                    $ratearray[$sheetTitlearray[0]] = $FormulaType;
                }
                if (in_array($FormulaType, array_map('strtoupper', $RateTypes)) && $FormulaType == $ratearray[$sheetTitlearray[0]]) {
                    $rate_type_code = ($FormulaType=="FAT+SNF" ?"1":"2");
                    $milk_type_code = array_search($sheetTitlearray[0], array_map('strtolower', $animalTypedata));
                    $milk_quality_type_code = array_search($sheetTitlearray[1], array_map('strtolower', $milkQuality->getActiveQualityType()));
                    $quality_param = explode('+', $FormulaType);
                    if (count($quality_param) == 1 && $worksheet->getHighestColumn() != 'B') {
                        return ['status' => 'error', 'message' => 'Invalid Sheet Format [' . $sheetTitle . ']'];
                    } else {
                        $purchaseBasedModel = new TblTankerRateBased();
                        $purchaseBasedModel->tanker_rate_code = $purchaseRate->tanker_rate_code;
                        $purchaseBasedModel->rate_based_code = $purchaseBasedModel->tanker_rate_code . ($purchaseBasedModel->getCode() + $baseCode);
                        $purchaseBasedModel->milk_type_code = $milk_type_code;
                        $purchaseBasedModel->rate_type_code = $rate_type_code;
                        $purchaseBasedModel->fat_rate = number_format((float) $worksheet->getCell('A2')->getValue(), 1);
                        $purchaseBasedModel->fat_ratio = number_format((float) $worksheet->getCell('A' . $worksheet->getHighestRow())->getValue(), 1);
                        $purchaseBasedModel->milk_quality_type_code = $milk_quality_type_code;
                        $purchaseBasedModel->originating_type = 2;
                        $purchaseBasedModel->originating_org_code = Yii::$app->session->get('organizations_code');
                        $purchaseBasedModel->originating_org_type = Yii::$app->session->get('organizations_type');

                        if (count($quality_param) > 1) {
                         $purchaseBasedModel->snf_rate = number_format((float) $worksheet->getCell('B1')->getValue(), 1);
                         $purchaseBasedModel->snf_ratio= number_format((float) $worksheet->getCell($worksheet->getHighestColumn(1) . '1')->getValue(), 1);
                        }
                         $based[] = $purchaseBasedModel;
                          $baseCode++;
                        for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
// Row range missing validation
                            if ($row != 2) {
                                $oldrowrange = floatval($worksheet->getCell('A' . ($row - 1))->getValue());
                                $newrowrange = floatval($worksheet->getCell('A' . $row)->getValue());
                                if ((round(($newrowrange - $oldrowrange), 1) !== 0.1)) {
                                    return [
                                        'status' => 'error',
                                        'message' => 'Invalid Sheet Format(Range Missing row) [' . $sheetTitle . ']'
                                    ];
                                }
                            }
                            $HighestColumn = $worksheet->getHighestColumn();
                            $HighestcolumnIndex = PHPExcel_Cell::columnIndexFromString($HighestColumn);
                            $HighestColumnplus = PHPExcel_Cell::stringFromColumnIndex($HighestcolumnIndex);
                            for ($col = 'B'; $col != $HighestColumnplus; $col++) {
// Column range missing validation
                                $cell = $worksheet->getCell($col . $row)->getValue();
                                $columnIndex = PHPExcel_Cell::columnIndexFromString($col);
                                if ($col != 'B') {
                                    $oldcolrange = floatval($worksheet->getCell((PHPExcel_Cell::stringFromColumnIndex($columnIndex - 2)) . '1')->getValue());
                                    $newcolrange = floatval($worksheet->getCell($col . '1')->getValue());
                                    if ((round(($newcolrange - $oldcolrange), 1) !== 0.1)) {
                                        return [
                                            'status' => 'error',
                                            'message' => 'Invalid Sheet Format (Range Missing col) [' . $sheetTitle . ']'
                                        ];
                                    }
                                }
                                if (is_float($cell)) {
                                    $currentcell = floatval($cell);
                                    $previouscell = floatval($worksheet->getCell((PHPExcel_Cell::stringFromColumnIndex($columnIndex - 2)) . $row)->getValue());
                                    $previousrow = floatval($worksheet->getCell($col . ($row - 1))->getValue());
                                    if ($row == 2) {
                                        if ($col != 'B' && $currentcell < $previouscell) {
                                            $error = TRUE;
                                            $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
                                        }
                                    } else {
                                        if ($col == 'B' && $currentcell < $previousrow) {
                                            $error = TRUE;
                                            $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
                                        } else if ($col != 'B') {
                                            if ($currentcell < $previouscell) {
                                                $error = TRUE;
                                                $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
                                            }
                                        }
                                    }
                                    if (!$error) {
                                        $data [$i] [] = [
                                            // $purchaseRate->tanker_rate_code . ($purchaseModel->getCode() + $cnt),
                                            $purchaseRate->tanker_rate_code,
                                            $rate_type_code,
                                            $milk_quality_type_code,
                                            $milk_type_code,
                                            number_format((float) $worksheet->getCell('A' . $row)->getValue(), 2),
                                            number_format((float) $worksheet->getCell($col . '1')->getValue(), 2),
                                            number_format((float) $cell, 2),
                                            \Yii::$app->session->get('organizations_code'),
                                            \Yii::$app->session->get('organizations_type'),
                                            2
                                        ];
                                        $cnt++;
                                        if (count($data [$i]) == 1000) {
                                            $i++;
                                        }
                                    }
                                } else {
                                    return [
                                        'status' => 'error',
                                        'message' => 'Invalid Value in Cell \'("' . $col . $row . '" of ' . $sheetTitle . ')\''
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    return ['status' => 'error', 'message' => 'Rate Type is Not Valid'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Excel Should Contain Valid Sheet Name To Upload Data'];
            }
        }
        if (!$error) {
            if (!empty($data)) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
//                    var_dump($purchaseRate);
//                    var_dump($based);
//                    var_dump($data);
                    $master[] = $purchaseRate->save();
                    foreach ($based as $b) {
                        $b->scenario = 'excel';
                        $error = $b->save();
                        $master[] = $error;
                    }
                    foreach ($data as $d) {
                        \Yii::$app->db->createCommand()->batchInsert('tbl_tanker_rate_details', ['tanker_rate_code', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code', 'fat', 'snf', 'rtpl', 'originating_org_code', 'originating_org_type', 'originating_type'], $d)->execute();
                    }
                    if ($transaction->isActive && !in_array(FALSE, $master)) {
                        $transaction->commit();
                        return ['status' => 'success', 'url' => \yii\helpers\Url::to(['tbl-tanker-rate-details/rate-chart', 'id' => $purchaseRate->tanker_rate_code, 'milk_type' => 1, 'milk_quality' => 1])];
                    }
                    return ['status' => 'error', 'message' => 'Data not Saved Due to transaction Error'];
                } catch (Exception $e) {
                    $transaction->rollback();
                    return ['status' => 'error', 'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
                }
            }
        } else {
            return ['status' => 'error', 'message' => 'Invalid Value in Following Cell : <br/>' . implode('<br/>', $errorarray) . ''];
        }

        return ['status' => 'error', 'message' => 'Excel Should Contain \'Cow\',\'Buffalo\',\'Mix\' Sheet Name To Upload Data'];
    }

    /**
     * Updates an existing TblTankerRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tanker_rate_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblTankerRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_purchase_rate ', [Yii::$app->request->post('id')], FALSE);
        $tanker_rate_code = Yii::$app->request->post('id');

        if ($valueOut == 0 && substr($code, 0, 11) == Yii::$app->session->get('Unions')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $master = [];
                $this->model = $this->findModel($tanker_rate_code);
                $historyModel = new TblTankerRate();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $master[] = $historyModel->save();
                $details = \app\modules\tankermovement\models\TblTankerRateBased::find()->where(['tanker_rate_code' => $tanker_rate_code])->all();
                foreach ($details as $key => $id) {
                    $detailHistory = new \app\modules\tankermovement\models\TblTankerRateBasedHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $master[] = $detailHistory->save();
                    $master[] = $details[$key]->delete();
                }
                $stateMap = TblTankerRateDetails::find()->where(['tanker_rate_code' => $tanker_rate_code])->deleteAll();

                $master[] = $this->model->delete();
                if (in_array(FALSE, $master) && !($transaction->isActive)) {
                    $transaction->rollback();
                    $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
                } else {
                    $transaction->commit();
                    $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
                }
            } catch (UserException $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => $e->getMessage()];
            } catch (\yii\db\Exception $e) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblTankerRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblTankerRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTankerRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single TblTankerRate model.
     * @param string $id
     * @return mixed
     */
    public function actionTankerRateApplicability($id) {

        $this->model = new TblTankerRateApplicability();
        $this->model->create($id);
//        $searchModel = new TblTankerRateApplicabilitySearch();
//        $searchModel->tanker_rate_code=$this->model->tanker_rate_code;
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $selected=  $this->model->getParty();
//        if(!empty($this->model->union_code))
//        {
//            $party_list=  $this->loadParty($this->model->union_code);
//        }
//        else
//        {
//            $party_list=[];
//        }

        if ($this->model->load(Yii::$app->request->post())) {
            $appmodel = new TblTankerRateApplicability();
            $model = $this->findModel($id);
            $appmodel->shift_code = $model->shift_code;
            $appmodel->wef_date = $model->wef_date;
            $is_union = false;
            $union_code = $model->union_code;
            $field_name = 'tanker_rate_code';
            $field_value = $id;
            $trans_label = 'purchase rate applicability';

            $fields = [
                'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function ($model) {
                        return Yii::$app->controls->view_date($model->wef_date);
                    }],
                'shift_code' => ['view' => ['grid', 'create'], 'type' => 'dropdown', 'flag' => 'shift_applicability', 'value' => 'shiftCode.shift'],
                'party_master_code' => ['view' => ['grid'], 'value' => 'party_master_code'],
                'party_name' => ['view' => ['grid'], 'value' => function ($model) {
                        return \Yii::$app->general->getforeignkey($model->partyMasterCode, 'party_name');
                    }],
            ];
            $username = explode('#', Yii::$app->session->get('UserName'))[1];
            $shift_type = 'all';
            $ratechart = true;
            $isApproval = true;
            // $appModel->dcs_filters = ['society' => Yii::t('app', 'Society'), 'routes' => 'Routes', 'mcc' => 'MCC'];
        }
        return $this->render('../tbl-tanker-rate-applicability/create', [
                    'model' => $this->model, 'purchaseRate' => $this->purchaseRate,
                    'party_list' => $party_list, 'selected' => $selected,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }
}
