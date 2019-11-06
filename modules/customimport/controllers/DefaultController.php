<?php

namespace app\modules\customimport\controllers;

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
use app\modules\customimport\models\ImportForm;
use ReflectionClass;

/**
 * Default controller for the import module
 */
class DefaultController extends \app\controllers\ChildController {

    public $importClass = '\ruskid\csvimporter\CustomARImportStrategy';
    public $old_att = []; //array('dcs', 'animal_type_code');
    public $change_att = []; //array('society', 'milk_type_code');

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex($flag, $selected = '', $required = '', $type = 'create') {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();

            $ext = pathinfo($post['file_name'], PATHINFO_EXTENSION);
            //echo $ext; exit;
            $data = \app\modules\customimport\importData::getLabels($flag);
            if (!empty($selected)) {
                $data['fields'] = $selected;
                $data['scenario'] = 'customImport';
            }
            //var_dump($data['fields']); exit;
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
            $historyClass = !empty($data['history_class']) ? Yii::$app->path->define($data['history_class']) : '';
            $data['history_class'] = !empty($historyClass) ? $historyClass : '';

            if ($ext == 'csv') {
                $values = $this->importCsv($post['file_name'], $className, $data, $post['mapping'], $flag, $required, $type);
            } else if ($ext == 'xls' || $ext == 'xlsx') {
                $values = $this->importExcel($post['file_name'], $className, $data, $flag);
            }
        }
        $result = ['status' => $values['status'], 'data' => $values['msg']];
        echo (Json::encode($result));
    }

    public function importCsv($fileName, $className, $data, $mappingFlag = 0, $flag, $required, $type) {

        try {

            if (!empty($data['import_main_class']) && $mappingFlag != 1) {
                $importClass = Yii::$app->path->get($data['import_main_class']);
                $this->importClass = $importClass . $data['import_main_class'];
            }
            if ($type == 'update' && !empty($data['import_update_class']) && $mappingFlag != 1) {
                $importClass = Yii::$app->path->get($data['import_update_class']);
                $this->importClass = $importClass . $data['import_update_class'];
            }
            $importer = new CSVImporter();
            $fields = $data['fields'];
            $scenario = !empty($data['scenario']) ? $data['scenario'] : '';
            if ($mappingFlag == 1) {
                $fields = $data['mapping_fields'];
                $scenario = '';
            }
            $fields = explode(',', $fields);

            foreach ($fields as $i => $d) {
                $config_value[$i]['attribute'] = trim($d);
                $config_value[$i]['value'] = function($line)use ($i) {
                    return $line[$i];
                    //return isset($line[$i])?$line[$i]:0;
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
                    /* $codes = $this->getCodes();
                      $fields = explode(',', $codes);
                      foreach ($fields as $i => $d) {
                      $value = explode(':', $d);
                      $default_value[$count]['attribute'] = $value[0];
                      $default_value[$count]['value'] = $value[1];
                      $count++;
                      } */
                }
            }
            $importer->setData(new CSVReader([
                'filename' => Yii::$app->basePath . '/web/import/' . trim($fileName),
                'fgetcsvOptions' => [
                    'delimiter' => ';'
                ]
            ]));
            //          print_r($importer);
            //        exit;
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
                'required' => $required,
                'historyClass' => isset($data['history_class']) ? $data['history_class'] : '',
                'type' => $type,
                'updateField' => !empty($data['update_field']) ? $data['update_field'] : '',
            ]));

            return ['status' => $primaryKeys['status'], 'msg' => $primaryKeys['msg']];
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
    public function actionDownloadSample($flag, $localField = 'local_name', $selected = '', $custom = true) {

        $data = \app\modules\customimport\importData::getLabels($flag);
        $header = \app\modules\translation\Translation::getContentHeaders('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $column = 'A';

        $a = str_replace('local_name', $localField, $data['fields']);
        $a = str_replace($this->old_att, $this->change_att, $a);
        $fields = explode(',', $a);
        if (!empty($selected)) {
            $selected = explode(',', $selected);
            $fields = $selected; //array_intersect($selected, $fields);
        }
        $modelName = str_replace('_', ' ', $data['table_name']);
        $modelName = str_replace(' ', '', ucwords($modelName));

        $className = Yii::$app->path->getModel($modelName);
        $class = new $className;
        $fields = array_map(function($str) use ($class) {
            return ucwords(str_replace('_', ' ', $str));
            //return empty($class->attributeLabels()[trim($str)])?ucwords(str_replace('_', ' ', $str)):$class->attributeLabels()[trim($str)];
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
        $data = \app\modules\customimport\importData::getLabels(Yii::$app->request->post('type'));
        $fields = (!empty(Yii::$app->request->post('sel')) && Yii::$app->request->post('sel') == 1) ? $data['mapping_fields'] : $data['fields'];
        $array = str_replace(',', ', ', $fields);
        echo (Json::encode($array));
        //}
    }

    public function actionGetFieldsOps() {
        $flag = Yii::$app->request->post('flag');
        if (!empty(Yii::$app->request->post('type'))) {
            $data = \app\modules\customimport\importData::getLabels(Yii::$app->request->post('type'));
            $fields = $data['fields'];
            $fieldsAry = array_map('trim', explode(',', $fields));
            $defalut_fields = $data['operations'][$flag];
            $defalut_fields_ary = !empty($defalut_fields) ? array_map('trim', explode(',', $defalut_fields)) : [];
            $updateFields = [];
            if ($flag == 'update' && !empty($data['update_field'])) {
                $updateFields = array_map('trim', explode(',', $data['update_field']));
                $defalut_fields_ary = !empty($defalut_fields_ary) ? array_merge($defalut_fields_ary, $updateFields) : $updateFields;
            }
            if ($flag == 'update' && !empty($data['no_update'])) {
                $noUpdateFields = array_map('trim', explode(',', $data['no_update']));
                $defalut_fields_ary = array_diff($defalut_fields_ary, $noUpdateFields);
                $fieldsAry = array_diff($fieldsAry, $noUpdateFields);
            }
            $defalut_fields_ary = array_unique($defalut_fields_ary);
            $fieldsAry = array_diff($fieldsAry, $defalut_fields_ary);
            $fieldsAry = str_replace($this->old_att, $this->change_att, $fieldsAry);
            $fields = implode(', ', $fieldsAry);
            $defalut_fields = implode(', ', $defalut_fields_ary);
            $defalut_fields = str_replace($this->old_att, $this->change_att, $defalut_fields);
            echo (Json::encode(['fields' => $fields, 'def_fields' => $defalut_fields]));
        } else {
            echo (Json::encode(['fields' => '', 'def_fields' => '']));
        }
        //}
    }

    private function getCodes() {
        if (!empty(Yii::$app->request->post('TblMember')['dcs_code'])) {
            $dcs = TblDcs::findOne(Yii::$app->request->post('TblMember')['dcs_code']);
            $union = $dcs->union_code;
            $fed = $dcs->unionCode->federation_code;
            $codes = 'dcs_code:' . Yii::$app->request->post('TblMember')['dcs_code'] . ',union_code:' . $union . ',federation_code:' . $fed;
            return $codes;
        }
    }

    public function importForm($flag) {
        $model = new ImportForm();
        $data = [];
        if ($flag == 'create' || $flag == 'update') {
            $data = \app\modules\customimport\importData::getData($flag);
            $data = array_keys($data);
        }

        return $this->render('_import_form', [
                    'model' => $model,
                    'data' => $data,
                    'flag' => $flag
        ]);
    }

    public function actionImportFormSort() {
        $model = new ImportForm();
        $request = Yii::$app->request->queryParams;
        if (!empty($request['ImportForm'])) {

            return $this->render('_import_form_sort', [
                        'model' => $model,
                        'type' => $request['ImportForm']['module_name'],
                        'selected' => $request['ImportForm']['fields'],
                        'flag' => $request['flag']
            ]);
        }
    }

    public function actionImport() {
        $request = Yii::$app->request->queryParams;
        if (!empty($request['ImportForm'])) {
            //var_dump($_REQUEST['ImportForm']['fields']);exit;
            return $this->render('_import_area', [
                        'type' => $request['ImportForm']['module_name'],
                        'selected' => $request['ImportForm']['fields'],
                        'required' => $request['ImportForm']['required'],
                        'flag' => $request['flag']
            ]);
        }
    }

    public function actionCreateByImport() {
        return $this->importForm('create');
    }

    public function actionUpdateByImport() {
        return $this->importForm('update');
    }

}
