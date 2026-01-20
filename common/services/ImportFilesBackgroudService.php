<?php

namespace common\services;

use app\modules\import\models\TblImportFileLog;
use Yii;
use app\modules\import\controllers\DefaultController;
use app\modules\import\importData;
use PHPExcel;

class ImportFilesBackgroudService {

    public function ProcessImportFilesBackground() {
        $model = new TblImportFileLog();
        $model->status = 0;
        $modelData = $model->getPickRecords([], 1, ['background'], false);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->log_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            foreach ($modelData as $row) {
                $model->updateCronPickedDate($row);
                $this->bulk_files_data($row);
            }
            return true;
        } else {
            return false;
        }
    }

    private function bulk_files_data($row) {
        try {
            $error_lines = [];
            $total_cnt = 0;
            $data = importData::getLabels($row->file_type);
            $table = (!empty($data['import_class'])) ? $data['import_class'] : $data['table_name'];
            $modelName = str_replace('_', ' ', $table);
            $modelName = str_replace(' ', '', ucwords($modelName));
            $className = Yii::$app->path->getModel($modelName);
            $eiplcode = Yii::$app->general->getClientCode($row->union_code);
            $unionKeyPattern = Yii::$app->general->getUnionKeyPattern($row->union_code);
            $unionConfigData = Yii::$app->general->getAllUnionWiseConfig($row->union_code, 'PORTAL');
            foreach ($unionConfigData as $configData) {
                $data['import_union_config'][$configData->config_key] = $configData->config_result_key;
            }
            $data['import_union_code'] = $row->union_code;
            $data['import_eipl_code'] = $eiplcode;
            $data['import_key_pattern'] = $unionKeyPattern;
            $data['created_by'] = $row->created_by;
            $import = new DefaultController('', '');
            $values = $import->importCsv($row->file_name, $className, $data, 0, $row->file_type, '/web/bulkdata/' . $row->file_type . '/');

            $filePath = NULL;
            $successfilePath = NULL;
            $error_lines = [];
            $success_lines = [];
            if (!empty($values['allData']['error_lines'])) {
                $column_header = explode(',', $data['fields']);
                $column_header[] = 'response_msg';
                $error_lines = $values['allData']['error_lines'];
                $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web/bulkdata/' . $row->file_type . '/archive/';
                if (Yii::$app->general->checkDirectory($path)) {
                    $objPHPExcel = new PHPExcel();
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->fromArray(
                            $column_header, // The data to set
                            NULL, // Array values with this value will not be set
                            'A1'         // Top left coordinate of the worksheet range where
                            //    we want to set these values (default is A1)
                    );
                    $sheet->fromArray(
                            $error_lines, // The data to set
                            NULL, // Array values with this value will not be set
                            'A2'         // Top left coordinate of the worksheet range where
                            //    we want to set these values (default is A1)
                    );
                    $filePath = $path . 'error_' . $row->file_name;
                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save($filePath);
                    copy($row->file_path, $path . $row->file_name);
                    unlink($row->file_path);
                    $filePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'error_' . $row->file_name;
                }
            }
            if (!empty($values['allData']['success_lines'])) {
                $column_header = explode(',', $data['fields']);
                $success_lines = $values['allData']['success_lines'];
                $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web/bulkdata/' . $row->file_type . '/archive/';
                if (Yii::$app->general->checkDirectory($path)) {
                    $objPHPExcel = new PHPExcel();
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->fromArray(
                            $column_header, // The data to set
                            NULL, // Array values with this value will not be set
                            'A1'         // Top left coordinate of the worksheet range where
                            //    we want to set these values (default is A1)
                    );
                    $sheet->fromArray(
                            $success_lines, // The data to set
                            NULL, // Array values with this value will not be set
                            'A2'         // Top left coordinate of the worksheet range where
                            //    we want to set these values (default is A1)
                    );
                    $successfilePath = $path . 'success_' . $row->file_name;
                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save($successfilePath);
                    $successfilePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'success_' . $row->file_name;
                }
            }
            $row->total_count = !empty($values['allData']['total_cnt']) ? $values['allData']['total_cnt'] : $total_cnt;
            $row->error_count = count($error_lines);
            $row->success_count = $row->total_count - $row->error_count;
            $row->status = 2;
            $row->response_datetime = date('Y-m-d H:i:s');
            $row->response_msg = 'File Processed';
            $row->error_file_path = $filePath;
            $row->success_file_path = $successfilePath;
            $row->save(FALSE);
        } catch (\Throwable $ex) {
            $row->status = 3;
            $row->response_msg = 'Unable to read file.';
            $row->response_datetime = date('Y-m-d H:i:s');
            $row->save(FALSE);
        }
    }

}
