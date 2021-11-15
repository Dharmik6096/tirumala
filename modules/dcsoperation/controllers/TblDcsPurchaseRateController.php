<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\dcsoperation\models\TblDcsPurchaseRateSearch;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\dcsoperation\models\TblRateType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblQualityParam;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBased;
use ReflectionClass;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use PHPExcel_Cell;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabititySearch;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityHistory;
use yii\helpers\Json;
use PHPExcel;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityHistory;
use webvimark\modules\UserManagement\components\GhostHtml;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use yii\helpers\Url;

/**
 * TblDcsPurchaseRateController implements the CRUD actions for TblDcsPurchaseRate model.
 */
class TblDcsPurchaseRateController extends \app\controllers\ChildController {

    public $purchaseModel;
    public $freeAccessActions = ['chart-list'];

    /**
     * Lists all TblDcsPurchaseRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsPurchaseRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->purchaseModel = new TblDcsPurchaseRateBased();
        if (isset(Yii::$app->request->post()['templet-download'])) {
            $transaction = Json::decode($_POST['range_table']);
            if (count($transaction) > 0) {
                $objPHPExcel = new PHPExcel();
                $sheetcnt = 0;
            }
            for ($i = 0; $i < count($transaction); $i++) {
                // $milk_quality = $transaction[$i]['milk_quality'];
                // $milk_type = $transaction[$i]['milk_type'];
                $basedmodel = new TblDcsPurchaseRateBased();
                $basedmodel->milk_type_code = $transaction[$i]['milk_type_code'];
                $basedmodel->milk_quality_type_code = $transaction[$i]['milk_quality_type_code'];
                $milk_type = $basedmodel->milkTypeCode->oldAttributes['animal_type_name'];
                $milk_quality = $basedmodel->milkQualityTypeCode->oldAttributes['milk_quality_type_name'];

                $rate_type = $transaction[$i]['rate_type'];
                $fat_start = $transaction[$i]['fat_start'];
                $fat_end = $transaction[$i]['fat_end'];
                $snf_start = isset($transaction[$i]['snf_start']) ? $transaction[$i]['snf_start'] : 0;
                $snf_end = isset($transaction[$i]['snf_end']) ? $transaction[$i]['snf_end'] : 0;

                $rowCount = 1;
                $column = 'A';
                if ($sheetcnt == 0) {
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->setTitle($milk_type . '-' . $milk_quality);
                } else {
                    $sheet = $objPHPExcel->createSheet();
                    $sheet->setTitle($milk_type . '-' . $milk_quality);
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
                        // }
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
                        //}
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
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
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
     * Displays a single TblDcsPurchaseRate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $purchaseBasedModel = new TblDcsPurchaseRateBased();
        $purchaseBasedModel->purchase_rate_code = $id;
        $appsearchModel = new TblDcsPurchaseRateApplicabititySearch();
        $appsearchModel->purchase_rate_code = $id;
        $appdataProvider = $appsearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'purchaseBasedModel' => $purchaseBasedModel, 'appsearchModel' => $appsearchModel,
                    'appdataProvider' => $appdataProvider,
        ]);
    }

    /**
     * Creates a new TblDcsPurchaseRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDcsPurchaseRate();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date) ? Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT) : '';
            $this->model->wef_date = $this->model->wef_date . ' ' . \Yii::$app->general->getshift($this->model->shift_id);
            $this->model->purchase_rate_code = $this->model->getCode();
            $this->model->is_active = 1;
            $this->model->originating_org_code = Yii::$app->session->get('organizations_code');
            $this->model->originating_org_type = Yii::$app->session->get('organizations_type');
            $this->model->union_code = $this->model->union_code;

//$this->model->scenario = 'create';
            if ($this->model->validate()) {
                if ($this->model->rate_gen_method_code == 3) {
                    $this->model->originating_type = 2;
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $this->uploadExcel($_POST['file_name'], $this->model);
                }
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['tbl-dcs-purchase-rate-details/create-rate', 'id' => -1, 'method' => $this->model->rate_gen_method_code]);
                return ['status' => $result, 'url' => $url, 'originating_org_type' => $this->model->originating_org_type, 'originating_org_code' => $this->model->originating_org_code, 'rate_method' => $this->model->rate_gen_method_code, 'wef_date' => $this->model->wef_date, 'shift' => $this->model->shift_applicability, 'description' => $this->model->description, 'shift_id' => $this->model->shift_id, 'union_code' => $this->model->union_code];
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

        $objPHPExcel = \PHPExcel_IOFactory::load(IMPORT_PATH . $fileName);
        $data = [];
        $rateTypeModel = new TblRateType();
        $milkTypeModel = new TblAnimalType();
        $i = 0;
        $cnt = 0;
        $purchaseModel = new TblDcsPurchaseRateDetails();
        $detailmaxID = $purchaseModel->getCode();
        $based = [];
        $baseCode = 0;
        $error = FALSE;
        $errorarray = [];
        $ratearray = [];
        $allowCopy = FALSE;
        $rateClass = 0;
        $purchaseBasedModel = new TblDcsPurchaseRateBased();
        $basemaxID = $purchaseBasedModel->getCode();

        $qualityModel = new TblQualityParam();
        $animalTypedata = $milkTypeModel->getRecords();
        $animalTypedata = ArrayHelper::getColumn($animalTypedata, 'oldAttributes');
        $SheetNames = ArrayHelper::getColumn($animalTypedata, 'animal_type_name');
        $animalTypedata = \yii\helpers\ArrayHelper::map($animalTypedata, 'animal_type_code', 'animal_type_name');
        $milkTypedata = $rateTypeModel->getRecords();
        $RateTypes = ArrayHelper::getColumn($milkTypedata, 'rate_type');
        $milkTypedata = \yii\helpers\ArrayHelper::map($milkTypedata, 'code', 'rate_type');
        $milkQuality = new TblMilkQualityType();
        $QualityType = array_values($milkQuality->getActiveQualityType());

        $arraycnt = [];
        $validSheet = TRUE;
        $arraySheet = [];
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $sheetTitle = strtolower($worksheet->getTitle());
            $sheetTitlearray = explode('-', $sheetTitle);
            $rateCatogory = ["A", "B", "C"];
            if ((count($sheetTitlearray) == 2 && in_array($sheetTitlearray[0], array_map('strtolower', $SheetNames)) && in_array($sheetTitlearray[1], array_map('strtolower', $QualityType))) || (count($sheetTitlearray) == 3 && in_array(strtoupper($sheetTitlearray[2]), $rateCatogory))) {
                //check allow to copy member ratechart for milk qlty good
                if (!empty($sheetTitlearray[2])) {
                    if (!$allowCopy) {
                        $allowCopy = TRUE;
                        $rateClass = !empty($sheetTitlearray[2]) ? (strtoupper($sheetTitlearray[2]) == 'A' ? 1 : (strtoupper($sheetTitlearray[2]) == 'B' ? 2 : (strtoupper($sheetTitlearray[2]) == 'C' ? 3 : 0))) : 0;
                    }
                }
                //Rate Class A is must if rate class available
                if (!empty($sheetTitlearray[2])) {
                    $key = $sheetTitlearray[0] . '-' . $sheetTitlearray[1];

                    if (!empty($arraySheet[$key])) {
                        if (strtoupper($arraySheet[$key]) != 'A') {
                            $arraySheet[$key] = $sheetTitlearray[2];
                        }
                    } else {
                        $arraySheet[$key] = $sheetTitlearray[2];
                    }
                }

                //Rate Class only allow for Good
//                if (!empty($sheetTitlearray[2]) && strtolower($sheetTitlearray[1] != 'good')) {
//                    $validSheet = FALSE;
//                }
            } else {
                $validSheet = FALSE;
            }
        }

        foreach ($arraySheet as $class) {
            if (strtoupper($class) != 'A') {
                $validSheet = FALSE;
            }
        }

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $sheetTitle = strtolower($worksheet->getTitle());
            $sheetTitlearray = explode('-', $sheetTitle);
//            $rateCatogory = ["A", "B", "C"];
//            if (($validSheet) && (count($sheetTitlearray) == 2 && in_array($sheetTitlearray[0], array_map('strtolower', $SheetNames)) && in_array($sheetTitlearray[1], array_map('strtolower', $QualityType))) || (count($sheetTitlearray) == 3 && in_array(strtoupper($sheetTitlearray[2]), $rateCatogory))) {
            if ($validSheet) {
                $FormulaType = strtoupper($worksheet->getCell('A1')->getValue());
                if (!isset($ratearray[$sheetTitlearray[0]])) {
                    $ratearray[$sheetTitlearray[0]] = $FormulaType;
                }
                $rate_class = !empty($sheetTitlearray[2]) ? (strtoupper($sheetTitlearray[2]) == 'A' ? 1 : (strtoupper($sheetTitlearray[2]) == 'B' ? 2 : (strtoupper($sheetTitlearray[2]) == 'C' ? 3 : 0))) : 0;
                if (in_array($FormulaType, array_map('strtoupper', $RateTypes)) && $FormulaType == $ratearray[$sheetTitlearray[0]]) {
                    $rate_type_code = array_search($FormulaType, array_map('strtoupper', $milkTypedata));
                    $milk_type_code = array_search($sheetTitlearray[0], array_map('strtolower', $animalTypedata));
                    $milk_quality_type_code = array_search($sheetTitlearray[1], array_map('strtolower', $milkQuality->getActiveQualityType()));
                    $quality_param = explode('+', $FormulaType);
                    if (count($quality_param) == 1 && $worksheet->getHighestColumn() != 'B') {
                        return ['status' => 'error', 'message' => 'Invalid Sheet Format [' . $sheetTitle . ']'];
                    } else {
                        $purchaseBasedModel = new TblDcsPurchaseRateBased();
                        $purchaseBasedModel->purchase_rate_code = $purchaseRate->purchase_rate_code;
//                        $purchaseBasedModel->rate_based_code = $purchaseBasedModel->purchase_rate_code . ($purchaseBasedModel->getCode() + $baseCode);
                        $purchaseBasedModel->rate_based_code = ($basemaxID + $baseCode);
                        $purchaseBasedModel->milk_type_code = $milk_type_code;
                        $purchaseBasedModel->rate_type = $rate_type_code;
                        $purchaseBasedModel->quality_param_code = array_search($quality_param[0], $qualityModel->getParams());
                        $purchaseBasedModel->start_range = number_format((float) $worksheet->getCell('A2')->getValue(), 1);
                        $purchaseBasedModel->end_range = number_format((float) $worksheet->getCell('A' . $worksheet->getHighestRow())->getValue(), 1);
                        $purchaseBasedModel->milk_quality_type_code = $milk_quality_type_code;
                        $purchaseBasedModel->originating_type = 2;
                        $purchaseBasedModel->rate_class = $rate_class;
                        $based[] = $purchaseBasedModel;
                        $baseCode++;
                        if (count($quality_param) > 1) {
                            $h = new ReflectionClass($purchaseBasedModel->className());
                            $newModel = $h->newInstanceArgs();
                            $attribute = $purchaseBasedModel->attributes;
                            $newModel->setAttributes($attribute);
//                            $newModel->rate_based_code = $purchaseBasedModel->purchase_rate_code . ($newModel->getCode() + $baseCode);
                            $newModel->rate_based_code = ($basemaxID + $baseCode);
                            $newModel->quality_param_code = array_search($quality_param[1], $qualityModel->getParams());
                            $newModel->start_range = number_format((float) $worksheet->getCell('B1')->getValue(), 1);
                            $newModel->end_range = number_format((float) $worksheet->getCell($worksheet->getHighestColumn(1) . '1')->getValue(), 1);
                            $newModel->originating_type = 2;
                            $newModel->rate_class = $rate_class;
                            $based[] = $newModel;
                            $baseCode++;
                        }
                        for ($row = 2; $row <= $worksheet->getHighestRow(); $row ++) {
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
                            for ($col = 'B'; $col != $HighestColumnplus; $col ++) {
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
//                                    $currentcell = floatval($cell);
//                                    $previouscell = floatval($worksheet->getCell((PHPExcel_Cell::stringFromColumnIndex($columnIndex - 2)) . $row)->getValue());
//                                    $previousrow = floatval($worksheet->getCell($col . ($row - 1))->getValue());
//                                    if ($row == 2) {
//                                        if ($col != 'B' && $currentcell < $previouscell) {
//                                            $error = TRUE;
//                                            $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
//                                        }
//                                    } else {
//                                        if ($col == 'B' && $currentcell < $previousrow) {
//                                            $error = TRUE;
//                                            $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
//                                        } else if ($col != 'B') {
//                                            if ($currentcell < $previouscell) {
//                                                $error = TRUE;
//                                                $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
//                                            }
//                                        }
//                                    }
                                    if (!$error) {
                                        $data [$i] [] = [
//                                            $purchaseRate->purchase_rate_code . ($purchaseModel->getCode() + $cnt),
                                            ($detailmaxID + $cnt),
                                            $purchaseRate->purchase_rate_code,
                                            $rate_type_code,
                                            $milk_quality_type_code,
                                            $milk_type_code,
                                            number_format((float) $worksheet->getCell('A' . $row)->getValue(), 2),
                                            number_format((float) $worksheet->getCell($col . '1')->getValue(), 2),
                                            number_format((float) $cell, 2),
                                            \Yii::$app->session->get('organizations_code'),
                                            \Yii::$app->session->get('organizations_type'),
                                            2,
                                            $rate_class
                                        ];
                                        $cnt ++;
                                        if (count($data [$i]) == 1000) {
                                            $i ++;
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
                    $master[] = $purchaseRate->save();
                    foreach ($based as $b) {
                        $b->scenario = 'excel';
                        $error = $b->save();
                        $master[] = $error;
                    }
                    foreach ($data as $d) {
                        \Yii::$app->db->createCommand()->batchInsert('tbl_dcs_purchase_rate_details', ['code', 'purchase_rate_code', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code', 'fat', 'snf', 'rtpl', 'originating_org_code', 'originating_org_type', 'originating_type', 'rate_class'], $d)->execute();
                    }
                    $sp_param = [];
                    $sp_name = 'DB_JOB_PORTAL_Member_Rate_chart';
                    $sp_param[] = $purchaseRate->purchase_rate_code;
                    $sp_param[] = $rateClass;
                    $sp_param[] = $purchaseRate->for_member;

                    \Yii::$app->general->getSpData($sp_name, $sp_param, TRUE);
                    if ($transaction->isActive && !in_array(FALSE, $master)) {
                        $transaction->commit();
                        return ['status' => 'success', 'url' => \yii\helpers\Url::to(['tbl-dcs-purchase-rate-details/rate-chart', 'id' => $purchaseRate->purchase_rate_code, 'milk_type' => 1, 'milk_quality' => 1])];
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
     * Updates an existing TblDcsPurchaseRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->purchase_rate_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDcsPurchaseRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_purchase_rate ', [Yii::$app->request->post('id')], FALSE);
        $purchase_rate_code = Yii::$app->request->post('id');

        if ($valueOut == 0 && substr($code, 0, 11) == Yii::$app->session->get('Unions')) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $master = [];
                $this->model = $this->findModel($purchase_rate_code);
                $historyModel = new TblDcsPurchaseRate();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $master[] = $historyModel->save(FALSE);
                $details = \app\modules\dcsoperation\models\TblDcsPurchaseRateBased::find()->where(['purchase_rate_code' => $purchase_rate_code])->all();
                foreach ($details as $key => $id) {
                    $detailHistory = new \app\modules\dcsoperation\models\TblDcsPurchaseRateBasedHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $master[] = $detailHistory->save(FALSE);
                    $master[] = $details[$key]->delete();
                }
                $stateMap = TblDcsPurchaseRateDetails::find()->where(['purchase_rate_code' => $purchase_rate_code])->deleteAll();

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
     * Finds the TblDcsPurchaseRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsPurchaseRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsPurchaseRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single TblDcsPurchaseRate model.
     * @param string $id
     * @return mixed
     */
    public function actionCalc() {
        return $this->render('snf_calc');
    }

    public function actionPurchaseRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblDcsPurchaseRateApplicabitity();
        $customerType = new TblCustomerType();
        $customerType->union_code = $model->union_code;
        $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        $appModel->model->shift_code = $model->shift_id;
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'purchase_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'purchase rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->header_title = !empty($model->description) ? ' - ' . $id . ' (' . $model->description . ') ' : ' - ' . $id;
        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE, FALSE);
                }],
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'shift_code' => ['view' => ['grid', 'create'], 'type' => 'dropdown', 'flag' => 'shift_applicability', 'value' => 'shiftCode.shift'],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
            'mcc_name' => ['view' => ['grid'], 'value' => function($model) {
                    if ($model->applicable_for == 'PLANT') {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    } else if ($model->applicable_for == 'MCC') {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    } else if ($model->applicable_for == 'BMC') {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    } else if ($model->applicable_for == 'DCS') {
                        return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
                    }
                }],
                //'dcs_name' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],           
        ];
        $appModel->actions = [
            'delete' => ['option' => 'applicable_code,rate_app_code,tbl-dcs-purchase-rate/delete-applicability'],
            'generate_sentbox' => function ($url, $model)use ($id) {
                $class = !empty($model->purchaseRateCode->purchaseRateCode) ? '' : 'disabled';
                $options = ['title' => Yii::t('app', 'Export Sentbox'), 'class' => $class];
                return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-dcs-purchase-rate/export-sentbox', 'id' => $id, 'dcs_code' => $model->applicable_code, 'rate_type' => 'MEMBER', 'date' => date('Y-m-d', strtotime($model->wef_date))], $options);
            }];
        $appModel->shift_type = isset($model->shiftApplicability) ? strtolower($model->shiftApplicability->shift) : NULL;
        $appModel->ratechart = true;
//        $appModel->dcs_filters = ['MCC' => 'MCC', 'PLANT' => 'PLANT', 'VENDOR' => 'VENDOR'];
        $appModel->dcs_filters = $value;
        $appModel->check_wef_date = true;
        $appModel->generateMail = true;
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
//        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_dcs_purchase_rate_applicabitity', '', '', Yii::$app->request->post('id'), 'rate_app_code']);
//        if ($valueOut == 0) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblDcsPurchaseRateApplicabitityHistory();
            $record = TblDcsPurchaseRateApplicabitity::findOne(Yii::$app->request->post('id'));
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
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
//        } else {
//            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted.'];
//        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionChartList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $RateModel = new TblDcsPurchaseRate();
            $list = $RateModel->getRateChartList($value[0]);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
    }

    public function actionDeleteBulkApplicability() {
        $searchModel = new TblDcsPurchaseRateApplicabititySearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteApplicability';
        $searchModel->wef_date = !empty($searchModel->wef_date) ? $searchModel->wef_date : NULL;
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $where['rate_app_code'] = $data[0];
                    $modelUsed = $data[1];
                    if ($modelUsed == 'MEMBER') {
                        $existData = TblPurchaseRateApplicability::find()->where($where)->one();
                        $historyModel = new TblPurchaseRateApplicabilityHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                    } else {
                        $existData = TblDcsPurchaseRateApplicabitity::find()->where($where)->one();
                        $historyModel = new TblDcsPurchaseRateApplicabitityHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                    }
                    $saveModel[] = $historyModel;
                    $deleteModel[] = $existData;
                    $message = 'Purchase Rate Applicability';
                    $type = 'delete';
                }

                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('delete_bulk_applicability', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExportSentbox($id, $dcs_code, $rate_type, $date) {
        $res_data = [];
        $res_data['purchaseRate'] = NULL;
        $res_data['purchaseRateBased'] = [];
        $res_data['purchaseRateApplicability'] = NULL;
        $res_data['purchaseRateApplicabilityMultiple'] = [];
        $org_code = $dcs_code;
        $org_type = 'VLC';
        $multi_applicability = TRUE;
        $purchasemodel = new TblPurchaseRate();
        $app_model = new TblPurchaseRateApplicability();
        $rate = $purchasemodel->find()->where(['dcs_purchase_rate_code' => $id])->one();
        $exportPath = Yii::$app->basePath . '/web/export/';
        if (!is_dir($exportPath)) {
            mkdir($exportPath);
            chmod($exportPath, 0777);
        }
        $folerName = $org_code . '_DCS';
        $FolderPath = Yii::$app->basePath . '/web/export/' . $folerName . '/';
        $zipfolder = Yii::$app->basePath . '/web/export/' . $folerName;
        if (!is_dir($FolderPath)) {
            $oldmask = umask(0);
            mkdir($FolderPath, 0777, TRUE);
            umask($oldmask);
        } else {
            $files = glob($FolderPath . '*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
        }
        $respData = [];
        $respData['status'] = 'success';
        $respData['error'] = ['code' => 200, 'message' => []];
        $respData['data'] = [];
        if (!empty($rate)) {
            $res_data['purchaseRate'] = $rate->attributes;
            $based_data = [];
            $base_record = $rate->purchaseRateBased;
            foreach ($base_record as $b) {
                $based_data[] = $b->attributes;
            }
            $res_data['purchaseRateBased'] = $based_data;
            if ($multi_applicability) {
                $applicability_array = [];
                $app_model->purchase_rate_code = $rate->purchase_rate_code;
                $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
                $dcs_code = $orgDetail['dcs_code'];
                $bmc_code = $orgDetail['bmc_code'];
                $mcc_plant_code = $orgDetail['mcc_plant_code'];
                $plant_code = $orgDetail['plant_code'];
                $applicability_data = $app_model->getApplicability($rate->purchase_rate_code, $dcs_code, $date);
                $applicability_array [] = !empty($applicability_data->attributes) ? $applicability_data->attributes : '';
                $res_data['purchaseRateApplicabilityMultiple'] = $applicability_array;
            } else {
                $rate->app_org_code = $org_code;
                $res_data['purchaseRateApplicability'] = !empty($rate->purchaseRateApplicability) ? $rate->purchaseRateApplicability[0]->attributes : NULL;
            }
            $masterFile = $org_code . '_1_master__';
            $masterFileName = $FolderPath . $masterFile . '.txt';
            $respData['data'] = $this->underscoreToCamelCase($res_data);
            $masterFile = fopen($masterFileName, 'w+');
            fwrite($masterFile, json_encode($respData) . PHP_EOL);
            fclose($masterFile);

            $arrayKey = [];
            foreach ($base_record as $baseRate) {
                $key = $baseRate->milk_quality_type_code . '-' . $baseRate->milk_type_code . '-' . $baseRate->rate_class;
                if (!in_array($key, $arrayKey)) {
                    $rate = Yii::$app->general->getSpData('sp_app_amcs_v2_purchase_rate_detail', [$baseRate->purchase_rate_code, $baseRate->milk_quality_type_code, $baseRate->milk_type_code, $rate_type, $baseRate->rate_class]);
                    if (!empty($rate)) {
                        $rateArray = array_column($rate, 'detail');

                        $rateDetailFile = $org_code . '_2_detail_' . $baseRate->milk_type_code . '_' . $baseRate->milk_quality_type_code . '_' . $baseRate->rate_class . '__';
                        $rateDetailFileName = $FolderPath . $rateDetailFile . '.txt';

                        $respData['data'] = $rateArray;
                        $rateDetailFileData = fopen($rateDetailFileName, 'w+');
                        fwrite($rateDetailFileData, json_encode($respData) . PHP_EOL);
                        fclose($rateDetailFileData);
                    }
                    $arrayKey[] = $key;
                }
            }
        }
        $eipl_code = Yii::$app->session->get('eiplCode');
        if (!empty($eipl_code)) {
            $pass = substr($eipl_code, 0, 2) . $org_code . substr($eipl_code, 2);
        } else {
            $pass = 'ei' . $org_code . 'pl';
        }
//        var_dump($pass);die;
        if (is_file($zipfolder . '.zip'))
            unlink($zipfolder . '.zip'); // delete file
        Yii::$app->general->ZipOperation($zipfolder, TRUE, '', $pass, 'txt', 'zip');
        $files = glob($zipfolder . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        rmdir($zipfolder);
        $downlaodPath = $zipfolder . '.zip';
        $downlaodPath = Url::base('https') . $zipfolder . '.zip';
        $downlaodPath = Url::base('https') . '/web/export/' . $folerName . '.zip';
        header("Content-type:application/pdf");
        header('Content-Disposition: attachment; filename=' . $folerName . '.zip');
        header('Location: ' . $downlaodPath);
        exit();
    }

    public function getOrgDetail($type, $code, $is_string = TRUE) {
        $dcs_code = [];
        $bmc_code = [];
        $mcc_plant_code = [];
        $plant_code = [];
        $union_code = '';
        $model_data = [];
        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $dcs_code[] = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $bmc_code[] = $model_data->bmc_code;
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $plant_code[] = $model_data->plant_code;
            }
        } else if ($type == 'BMC') {
            $model = new TblDcsBmc();
            $model->bmc_code = $code;
            $model_data = $model->singleBmcData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlant->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->tblBmcGroup, 'p_bmc_code');
                $bmc_code[] = $model_data->bmc_code;
                $dcs_code = ArrayHelper::getColumn($model_data->dcsCodes, 'dcs_code');
                foreach ($model_data->tblBmcGroup as $bmc) {
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($bmc->tblDcsCode, 'dcs_code'));
                }
            }
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->bmcCodes, 'bmc_code');
                $dcs_code = ArrayHelper::getColumn($model_data->tblDcs, 'dcs_code');
                foreach ($model_data->tblMccPlantGroup as $mcc) {
                    $bmc_code = array_merge($bmc_code, ArrayHelper::getColumn($mcc->tblBmcCode, 'bmc_code'));
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($mcc->tblDcsCode, 'dcs_code'));
                }
            }
        }
        if ($is_string) {
            $dcs_code = implode('\',\'', $dcs_code);
            $bmc_code = implode('\',\'', $bmc_code);
            $mcc_plant_code = implode('\',\'', $mcc_plant_code);
            $plant_code = implode('\',\'', $plant_code);
            $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
            $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
            $mcc_plant_code = !empty($mcc_plant_code) ? '\'' . $mcc_plant_code . '\'' : $mcc_plant_code;
            $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
        }
        return ['dcs_code' => $dcs_code, 'bmc_code' => $bmc_code, 'mcc_plant_code' => $mcc_plant_code, 'plant_code' => $plant_code, 'union_code' => $union_code, 'model_data' => $model_data];
    }

    public function &underscoreToCamelCase(&$res_data) {
        if (is_array($res_data)) {
            $res_data = array_combine(array_map(function($str) {
                        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
                    }, array_keys($res_data)), array_values($res_data));
            foreach ($res_data as $key => $val) {
                if (is_array($res_data[$key])) {
                    $arr1 = array_combine(array_map(function($str) {
                                return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
                            }, array_keys($res_data[$key])), array_values($res_data[$key]));
                    $res_data[$key] = $arr1;
                    $this->underscoreToCamelCase($res_data[$key]);
                }
            }
            return $res_data;
        }
        return $res_data;
    }

}
