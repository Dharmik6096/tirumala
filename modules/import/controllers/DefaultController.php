<?php

namespace app\modules\import\controllers;

use yii;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use arogachev\excel\import\basic\Importer;
use app\modules\organisation\models\TblDcs;
use yii\base\UserException;
use yii\helpers\Html;
use PHPExcel;
use yii\web\Response;
use yii\helpers\Json;

/**
 * Default controller for the import module
 */
class DefaultController extends \app\controllers\ChildController {

    public $importClass = '\ruskid\csvimporter\ARImportStrategy';
    public $old_att = array('land_unit,', 'dcs', 'capacity,', 'animal_type_code', 'date_time_of_collection');
    public $change_att = array('convert_to,', 'society', 'capacity_code,', 'milk_type_code', 'collection_date');

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($flag) {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();

            $ext = pathinfo($post['file_name'], PATHINFO_EXTENSION);
            //echo $ext; exit;
            $data = \app\modules\import\importData::getLabels($flag);
            $table = (!empty($data['import_class'])) ? $data['import_class'] : $data['table_name'];
            $modelName = str_replace('_', ' ', $table);
            $modelName = str_replace(' ', '', ucwords($modelName));

            if ($post['mapping'] == '0') {
                $className = Yii::$app->path->getModel($modelName);
                //var_dump($modelName); exit;
            } else {
                // echo 'there'; exit;
                $className = Yii::$app->path->getModel($data['mapping_model']);
            }

            if ($ext == 'csv') {
                $values = $this->importCsv($post['file_name'], $className, $data, $post['mapping'], $flag);
            } else if ($ext == 'xls' || $ext == 'xlsx') {
                $values = $this->importExcel($post['file_name'], $className, $data, $flag);
            }
        }
        $result = ['status' => $values['status'], 'data' => $values['msg']];
        echo (Json::encode($result));
    }

    public function importCsv($fileName, $className, $data, $mappingFlag = 0, $flag, $filepath = '/web/import/') {
        try {

            if (!empty($data['import_main_class']) && $mappingFlag != 1) {
                $importClass = Yii::$app->path->get($data['import_main_class']);
                $this->importClass = $importClass . $data['import_main_class'];
            }
            $importer = new CSVImporter();
            $fields = $data['fields'];
            $scenario = !empty($data['scenario']) ? $data['scenario'] : '';
            if ($mappingFlag == 1) {
                $fields = $data['mapping_fields'];
                $scenario = !empty($data['mapping_scenario']) ? $data['mapping_scenario'] : '';
                $data['save_child'] = !empty($data['save_map_child']) ? TRUE : FALSE;
                $data['update_key'] = FALSE;
            }
            $fields = explode(',', $fields);
            $accept_old_template = (!empty($data['accept_old_template']) && $data['accept_old_template']) ? TRUE : FALSE;
            foreach ($fields as $i => $d) {
                $config_value[$i]['attribute'] = $d;
                $config_value[$i]['value'] = function($line)use ($i, $accept_old_template) {
                    return ($accept_old_template) ? (isset($line[$i]) ? $line[$i] : '') : $line[$i];
                };
            }

            $default_value = null;
            if (!empty($data['default_fields'])) {
                $fields = explode(',', $data['default_fields']);
                $count = 0;
                foreach ($fields as $i => $d) {
                    $value = explode(':', $d);
                    $default_value[$count]['attribute'] = $value[0];
                    $default_value[$count]['value'] = $value[1];
                    $count++;
                }
                if ($flag == 'member_limited') {
                    $codes = $this->getCodes();
                    $fields = explode(',', $codes);
                    foreach ($fields as $i => $d) {
                        $value = explode(':', $d);
                        $default_value[$count]['attribute'] = $value[0];
                        $default_value[$count]['value'] = $value[1];
                        $count++;
                    }
                }
            }
            $importer->setData(new CSVReader([
                'filename' => Yii::$app->basePath . $filepath . trim($fileName),
                'fgetcsvOptions' => [
                    'delimiter' => ';'
                ]
            ]));
            $compare_len = TRUE;
            if (isset($data['validate_length']) && ($data['validate_length'] == false)) {
                $compare_len = false;
            }

            if ($compare_len == TRUE) {
                $rows = count($importer->getData());
                $validate_rows = isset($data['validate_rows']) ? $data['validate_rows'] : 500;
                if ($rows > $validate_rows) {
                    return ['status' => 'error', 'msg' => 'Not Allowed More Then ' . $validate_rows . ' Records'];
                }
            }
            /*
              $childDefaultFields = explode(',', $data['child_default_field']);
              foreach($childDefaultFields as $i =>$d){
              $value=  explode(':',$d);
              $child_default_value[$i]['attribute']=$value[0];
              $child_default_value[$i]['value']=$value[1];
              }
              $primaryKeys = $importer->import(new components\MultipleImport([
              'className' => components\GeneralFunctions::getClassFromTable($data['table_name']),
              'insert'=>2,
              'configs' =>$config_value,
              'defaultFields'=>$default_value,
              'isChildImport'=>$data['is_child_import'],
              'multiple'=>[
              'childClassName'=>components\GeneralFunctions::getClassFromTable($data['child_table_name']),
              'childFieldsName'=>$data['child_field_name'],
              'childDefaultFields'=>$child_default_value
              ],
              ])); */
            //        $primaryKeys = $importer->import(new ARImportStrategy([
            $primaryKeys = $importer->import(new $this->importClass([
                'className' => $className::className(),
                //'className' => components\GeneralFunctions::getClassFromTable($data['table_name']),
                'insert' => 2,
                'configs' => $config_value,
                'defaultFields' => $default_value,
                'isIncrement' => isset($data['increment']) ? $data['increment'] : 0,
                'scenario' => $scenario,
                'updateField' => !empty($data['update_field']) ? $data['update_field'] : '',
                'saveChild' => !empty($data['save_child']) ? $data['save_child'] : 0,
                'details' => $data,
                'file_path' => Yii::$app->basePath . '/web/import/' . trim($fileName),
                'file_name' => trim($fileName)
            ]));

            return ['status' => $primaryKeys['status'], 'msg' => $primaryKeys['msg'], 'allData' => $primaryKeys];
        } catch (UserException $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function importExcel($fileName, $className, $data, $flag) {


        $fields = explode(',', $data['fields']);
        foreach ($fields as $i => $d) {
            $config_value[$i]['name'] = $d;
        }
        $fields = explode(',', $data['default_fields']);
        foreach ($fields as $i => $d) {
            $rowValue = explode(':', $d);
            $default_value[$rowValue[0]] = $rowValue[1];
        }
        if ($flag == 'member_limited') {
            $codes = $this->getCodes();
            $fields = explode(',', $codes);
            foreach ($fields as $i => $d) {
                $rowValue = explode(':', $d);
                $default_value[$rowValue[0]] = $rowValue[1];
            }
        }


        $importer = new Importer([
            'filePath' => Yii::$app->basePath . '/web/import/' . trim($fileName),
            'standardModelsConfig' => [
                    [
                    'className' => $className::className(),
                    'defalutValues' => $default_value,
                    'standardAttributesConfig' => $config_value
                ],],
        ]);
        if (!$importer->run()) {
            echo $importer->error;

            if ($importer->wrongModel) {
                echo Html::errorSummary($importer->wrongModel);
            }
        }
    }

    public function actionImportFile() {

        $path = Yii::$app->basePath . '/web/import/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }

        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = (($file->extension == 'csv') ? 'csv_' : 'excel_') . time() . '.' . $file->extension;
            if ($file->saveAs($path . $name)) {

                if ($file->extension != 'csv') {
                    switch ($file->extension) {
                        case 'xls':
                            $format = 'Excel5';
                            break;
                        case 'xlsx':
                            $format = 'Excel2007';
                            break;
                        case 'xml':
                            $format = 'Excel2003XML';
                            break;
                    }

                    $reader = \PHPExcel_IOFactory::createReader($format);
                    $reader->setReadDataOnly(true);
                    $excel = $reader->load($path . $name);
                    $name = (pathinfo($name, PATHINFO_FILENAME)) . '.csv';
                    $savePath = Yii::$app->basePath . '/web/import/' . $name;
                    $writer = \PHPExcel_IOFactory::createWriter($excel, 'CSV');
                    $writer->setDelimiter(';');
                    $writer->save($savePath);
                }
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

    public function actionImportRateExcel() {
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
     * Download sample of import file
     * @param type $flag
     */
    public function actionDownloadSample($flag, $localField = 'local_name', $mapping = 0) {

        $data = \app\modules\import\importData::getLabels($flag);
        $header = \app\modules\translation\Translation::getContentHeaders('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $column = 'A';
        $data_fields = $mapping == 1 ? $data['mapping_fields'] : $data['fields'];

        $a = str_replace('local_name', $localField, $data_fields);
        if (!empty($data['old_label'])) {
            $this->old_att = array_merge($this->old_att, $data['old_label']);
        }
        if (!empty($data['new_label'])) {
            $this->change_att = array_merge($this->change_att, $data['new_label']);
        }
        $a = str_replace($this->old_att, $this->change_att, $a);
        $fields = explode(',', $a);
        $fields = array_map(function($str) {
            return ucwords(str_replace('_', ' ', $str));
        }, $fields);

        foreach ($fields as $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $col);
            $column++;
        }

        $name = str_replace('Tbl', '', $flag);
        $name = str_replace('Local', '', $name);
        $fileName = "$name." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionGetFields() {

        //if(!empty($_POST['sel'])){
        $data = \app\modules\import\importData::getLabels(Yii::$app->request->post('type'));
        $fields = ($_POST['sel'] == 1) ? $data['mapping_fields'] : $data['fields'];
        $array = str_replace(',', ', ', $fields);
        if (!empty($data['old_label'])) {
            $this->old_att = array_merge($this->old_att, $data['old_label']);
        }
        if (!empty($data['new_label'])) {
            $this->change_att = array_merge($this->change_att, $data['new_label']);
        }
        $array = str_replace($this->old_att, $this->change_att, $array);
        echo (Json::encode($array));
        //}
    }

    private function getCodes() {
        if (!empty($_POST['TblMember']['dcs_code'])) {
            $dcs = TblDcs::findOne($_POST['TblMember']['dcs_code']);
            $union = $dcs->union_code;
            $fed = $dcs->unionCode->federation_code;
            $codes = 'dcs_code:' . $_POST['TblMember']['dcs_code'] . ',union_code:' . $union . ',federation_code:' . $fed;
            return $codes;
        }
    }

}
