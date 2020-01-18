<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateSearch;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblRateType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblQualityParam;
use app\modules\dcsoperation\models\TblPurchaseRateBased;
use ReflectionClass;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use PHPExcel_Cell;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilitySearch;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityHistory;
use yii\helpers\Json;
use PHPExcel;
use app\modules\organisation\models\TblDcs;

/**
 * TblPurchaseRateController implements the CRUD actions for TblPurchaseRate model.
 */
class TblPurchaseRateController extends \app\controllers\ChildController {

    public $purchaseModel;
    public $freeAccessActions = ['chart-list'];

    /**
     * Lists all TblPurchaseRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPurchaseRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->purchaseModel = new TblPurchaseRateBased();
        if (isset(Yii::$app->request->post()['templet-download'])) {
            $transaction = Json::decode($_POST['range_table']);
            if (count($transaction) > 0) {
                $objPHPExcel = new PHPExcel();
                $sheetcnt = 0;
            }
            for ($i = 0; $i < count($transaction); $i++) {
                $milk_type = $transaction[$i]['milk_type'];
                $rate_type = $transaction[$i]['rate_type_code'];
                $fat_start = $transaction[$i]['fat_start'];
                $fat_end = $transaction[$i]['fat_end'];
                $snf_start = isset($transaction[$i]['snf_start']) ? $transaction[$i]['snf_start'] : 0;
                $snf_end = isset($transaction[$i]['snf_end']) ? $transaction[$i]['snf_end'] : 0;

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
     * Displays a single TblPurchaseRate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $purchaseBasedModel = new TblPurchaseRateBased();
        $purchaseBasedModel->purchase_rate_code = $id;
        $appsearchModel = new TblPurchaseRateApplicabilitySearch();
        $appsearchModel->purchase_rate_code = $id;
        $appdataProvider = $appsearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'purchaseBasedModel' => $purchaseBasedModel, 'appsearchModel' => $appsearchModel,
                    'appdataProvider' => $appdataProvider,
        ]);
    }

    /**
     * Creates a new TblPurchaseRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblPurchaseRate();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date) ? Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT) : '';
            $this->model->wef_date = $this->model->wef_date . ' ' . \Yii::$app->general->getshift($this->model->shift_id);
            $this->model->purchase_rate_code = $this->model->getCode();
            $this->model->is_active = 1;
            //$this->model->originating_org_code = Yii::$app->session->get('organizations_code');
            $this->model->originating_org_type = Yii::$app->session->get('organizations_type');
//$this->model->scenario = 'create';
            if ($this->model->validate()) {
                if ($this->model->rate_gen_method_code == 3) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $this->uploadExcel($_POST['file_name'], $this->model);
                }
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['tbl-purchase-rate-details/create-rate', 'id' => -1, 'method' => $this->model->rate_gen_method_code]);
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
        $purchaseModel = new TblPurchaseRateDetails();
        // $detailmaxID = $purchaseModel->getCode();
        $based = [];
        $baseCode = 0;
        $error = FALSE;
        $errorarray = [];

        $purchaseBasedModel = new TblPurchaseRateBased();
        $basemaxID = $purchaseBasedModel->getCode();

        $qualityModel = new TblQualityParam();
        $animalTypedata = $milkTypeModel->getRecords();
        $SheetNames = ArrayHelper::getColumn($animalTypedata, 'animal_type_name');
        $animalTypedata = \yii\helpers\ArrayHelper::map($animalTypedata, 'animal_type_code', 'animal_type_name');
        $milkTypedata = $rateTypeModel->getRecords();
        $RateTypes = ArrayHelper::getColumn($milkTypedata, 'rate_type');
        $milkTypedata = \yii\helpers\ArrayHelper::map($milkTypedata, 'code', 'rate_type');
        $milkQuality = new TblMilkQualityType();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $sheetTitle = strtolower($worksheet->getTitle());
            if (in_array($sheetTitle, array_map('strtolower', $SheetNames))) {
                $FormulaType = strtoupper($worksheet->getCell('A1')->getValue());
                if (in_array($FormulaType, array_map('strtoupper', $RateTypes))) {
                    $rate_type_code = array_search($FormulaType, array_map('strtoupper', $milkTypedata));
                    $milk_type_code = array_search($sheetTitle, array_map('strtolower', $animalTypedata));
                    $quality_param = explode('+', $FormulaType);
                    if (count($quality_param) == 1 && $worksheet->getHighestColumn() != 'B') {
                        return ['status' => 'error', 'message' => 'Invalid Sheet Format [' . $sheetTitle . ']'];
                    } else {
                        $purchaseBasedModel = new TblPurchaseRateBased();
                        $purchaseBasedModel->purchase_rate_code = $purchaseRate->purchase_rate_code;
                        $purchaseBasedModel->rate_based_code = $basemaxID + $baseCode;
                        $purchaseBasedModel->milk_type_code = $milk_type_code;
                        $purchaseBasedModel->rate_type_code = $rate_type_code;
                        $purchaseBasedModel->quality_param_code = array_search($quality_param[0], $qualityModel->getParams());
                        $purchaseBasedModel->start_range = number_format((float) $worksheet->getCell('A2')->getValue(), 1);
                        $purchaseBasedModel->end_range = number_format((float) $worksheet->getCell('A' . $worksheet->getHighestRow())->getValue(), 1);
                        $purchaseBasedModel->milk_quality_type_code = array_search('good', array_map('strtolower', $milkQuality->getActiveQualityType()));

                        $based[] = $purchaseBasedModel;
                        $baseCode++;
                        if (count($quality_param) > 1) {
                            $h = new ReflectionClass($purchaseBasedModel->className());
                            $newModel = $h->newInstanceArgs();
                            $attribute = $purchaseBasedModel->attributes;
                            $newModel->setAttributes($attribute);
                            $newModel->rate_based_code = $basemaxID + $baseCode;
                            $newModel->quality_param_code = array_search($quality_param[1], $qualityModel->getParams());
                            $newModel->start_range = number_format((float) $worksheet->getCell('B1')->getValue(), 1);
                            $newModel->end_range = number_format((float) $worksheet->getCell($worksheet->getHighestColumn(1) . '1')->getValue(), 1);
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
//                                        if ($col != 'B') {
//                                            if ($currentcell < $previouscell) {
//                                                $error = TRUE;
//                                                $errorarray [] = 'Wrong Value at ' . $col . $row . ' [' . $sheetTitle . ']';
//                                            }
//                                        }
//                                    }
                                    if (!$error) {
                                        $data [$i] [] = [
                                            $purchaseRate->purchase_rate_code,
                                            $rate_type_code,
                                            $purchaseBasedModel->milk_quality_type_code,
                                            $milk_type_code,
                                            number_format((float) $worksheet->getCell('A' . $row)->getValue(), 2),
                                            number_format((float) $worksheet->getCell($col . '1')->getValue(), 2),
                                            number_format((float) $cell, 2),
                                            1,
                                            \Yii::$app->user->identity->user_code,
                                            date('Y-m-d H:i:s')
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
                        \Yii::$app->db->createCommand()->batchInsert('tbl_purchase_rate_details', ['purchase_rate_code', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code', 'fat', 'snf', 'rtpl', 'is_active', 'created_by', 'created_at'], $d)->execute();
                    }
                    if ($transaction->isActive && !in_array(FALSE, $master)) {
                        $transaction->commit();
                        return ['status' => 'success', 'url' => \yii\helpers\Url::to(['tbl-purchase-rate-details/rate-chart', 'id' => $purchaseRate->purchase_rate_code, 'milk_type' => 1])];
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
     * Updates an existing TblPurchaseRate model.
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
     * Deletes an existing TblPurchaseRate model.
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
                $historyModel = new TblPurchaseRate();
                Yii::$app->operation->history($this->model, $historyModel, DELETE);
                $master[] = $historyModel->save();
                $details = \app\modules\dcsoperation\models\TblPurchaseRateBased::find()->where(['purchase_rate_code' => $purchase_rate_code])->all();
                foreach ($details as $key => $id) {
                    $detailHistory = new \app\modules\dcsoperation\models\TblPurchaseRateBasedHistory();
                    Yii::$app->operation->history($id, $detailHistory, DELETE);
                    $master[] = $detailHistory->save();
                    $master[] = $details[$key]->delete();
                }
                $stateMap = TblPurchaseRateDetails::find()->where(['purchase_rate_code' => $purchase_rate_code])->deleteAll();

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
     * Finds the TblPurchaseRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblPurchaseRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPurchaseRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single TblPurchaseRate model.
     * @param string $id
     * @return mixed
     */
    public function actionCalc() {
        return $this->render('snf_calc');
    }

    public function actionPurchaseRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblPurchaseRateApplicability();
        $appModel->model->shift_code = $model->shift_id;
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'purchase_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'purchase rate applicability';
        $appModel->header_title = !empty($model->description) ? ' - ' . $id . ' (' . $model->description . ') ' : ' - ' . $id;
        $appModel->fields = ['wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'shift_code' => ['view' => ['grid', 'create'], 'type' => 'dropdown', 'flag' => 'shift_applicability', 'value' => 'shiftCode.shift'],
            'dcs_code' => ['view' => ['grid'], 'value' => 'dcs_code'],
            'dcs_name' => ['view' => ['grid'], 'value' => function($model) {
                    return \Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
            'is_download' => ['view' => ['grid'], 'type' => 'yes-no', 'value' => function($model) {
                    return ($model->is_download == 0) ? Yii::t('app', 'Done') : Yii::t('app', 'Pending');
                }],
            'download_date_time' => ['view' => ['grid'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->download_date_time);
                }],
        ];
        $username = explode('#', Yii::$app->session->get('UserName'))[1];
        if (!in_array(strtolower($username), ['bipl', 'reil']))
            $appModel->actions = ['delete' => ['option' => 'dcs_code,rate_app_code,tbl-purchase-rate/delete-rate-app,checkVendorDcs()']];
        $appModel->shift_type = isset($model->shiftApplicability) ? strtolower($model->shiftApplicability->shift) : NULL;
        $appModel->ratechart = true;
        $appModel->dcs_filters = ['society' => 'Society', 'routes' => 'Routes', 'mcc' => 'MCC'];

        return $appModel->createApp();
    }

    public function actionDeleteRateApp() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblPurchaseRateApplicabilityHistory();
            $record = TblPurchaseRateApplicability::find()->where(['rate_app_code' => Yii::$app->request->post('id')])->one();
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $model = new TblDcs();
            $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'rate_flag' => 2, 'member_rate_code' => $record->purchase_rate_code], ['dcs_code' => $record->dcs_code]);
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

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionChartList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $RateModel = new TblPurchaseRate();
            $list = $RateModel->getRateChartList($value[0]);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo \yii\helpers\Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
    }

}
