<?php

namespace common\services;

use app\modules\import\models\TblImportFileLog;
use Yii;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use app\modules\import\importData;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\modules\collection\models\TblBulkBillingImport;
use \app\modules\collection\models\TblBulkDataImport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFilesService {

    public function ProcessImportFiles() {
        $model = new TblImportFileLog();
        $model->status = 0;
        $modelData = $model->getPickRecords([], 10, ['SP']);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->log_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            foreach ($modelData as $row) {
                $model->updateCronPickedDate($row);
                $this->process_files_data($row);
            }
        }
    }

    private function process_files_data($row) {
        try {
            $flag = '';
            if ($row->file_type == 'bmc_collection') {
                $flag = 'bmc-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'milk_collection') {
                $flag = 'milk-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'milk_collection_dpu_data') {
                $flag = 'import-shagun-dpu-data';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'milk_collection_other_data') {
                $flag = 'import-other-dpu-data';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'milk_collection_qlty') {
                $flag = 'milk-collection-qlty-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'bmc_collection_mapped') {
                $flag = 'bmc-mapped-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_allow') {
                $flag = 'bmc-collection-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection_Allow';
            } else if ($row->file_type == 'milk_collection_allow') {
                $flag = 'milk-collection-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection_Allow';
            } else if ($row->file_type == 'milk_collection_qlty_allow') {
                $flag = 'milk-collection-qlty-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection_Allow';
            } else if ($row->file_type == 'bmc_collection_mapped_allow') {
                $flag = 'bmc-mapped-collection-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection_Allow';
            } else if ($row->file_type == 'bmc_collection_route') {
                $flag = 'bmc-collection-bulk-route';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_can') {
                $flag = 'bmc-collection-bulk-can';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_bmc_route') {
                $flag = 'bmc-collection-bulk-bmc-route';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_bmc_can') {
                $flag = 'bmc-collection-bulk-bmc-can';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_route_can') {
                $flag = 'bmc-collection-bulk-route-can';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_bmc_route_can') {
                $flag = 'bmc-collection-bulk-bmc-route-can';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection_antibiotic') {
                $flag = 'bmc-collection-bulk-antibiotic';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_weight_collection') {
                $flag = 'bmc-weight-collection';
                $sp_name = 'DB_JOB_PORTAL_WEIGHT_Collection';
            } else if ($row->file_type == 'bmc_quality_test') {
                $flag = 'bmc-quality-test';
                $sp_name = 'DB_JOB_PORTAL_QUALITY_Collection';
            } else if ($row->file_type == 'member_billing_import') {
                $flag = 'member-billing-bulk';
                $sp_name = 'DB_JOB_PORTAL_MEMBER_BILLING';
            } else if ($row->file_type == 'vendor_billing_import') {
                $flag = 'vendor-billing-bulk';
                $sp_name = 'DB_JOB_PORTAL_VSP_BILLING';
            } else if ($row->file_type == 'milk_collection_qty') {
                $flag = 'milk-collection-qty';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection_qty_wise';
            } else if ($row->file_type == 'sample_milk_collection') {
                $flag = 'sample-milk-collection';
                $sp_name = 'DB_JOB_PORTAL_Sample_Milk_Collection';
            }
            if (!empty($flag)) {
                $error_lines = [];
                $success = 0;
                $total_cnt = 0;
                $command = Yii::$app->getDb()->createCommand('SELECT NEWID() as id')->queryOne();
                $uuid = $command['id'];
                $importer = new CSVImporter();
                $importer->setData(new CSVReader([
                    'filename' => $row->file_path,
                    'fgetcsvOptions' => [
                        'delimiter' => ';'
                    ]
                ]));
                $config = importData::getLabels($flag);
                $header = explode(',', $config['fields']);
                $accept_old_template = (!empty($config['accept_old_template']) && $config['accept_old_template']) ? TRUE : FALSE;
                $fileData = $importer->getData();
                unset($fileData[0]);
                foreach ($fileData as $line) {
                    $total_cnt++;
                    if ($accept_old_template) {
                        $key_count_diff = count($header) - count($line);
                        $header_count = count($header);
                        while ($key_count_diff > 0) {
                            unset($header[$header_count - $key_count_diff]);
                            $key_count_diff -= 1;
                        }
                    }
                    $data = array_combine($header, $line);
                    if (($flag == 'member-billing-bulk') || ($flag == 'vendor-billing-bulk')) {
                        $model = new TblBulkBillingImport();
                        $model->attributes = $data;
                        $model->uuid = $uuid;
                        $model->union_code = $row->union_code;
                        if ($flag == 'member-billing-bulk') {
                            $model->billing_type = 'Member';
                            $model->customer_type = 'Member';
                        } else if ($flag == 'vendor-billing-bulk') {
                            $model->billing_type = 'vendor_billing';
                            $model->customer_type = 'DCS';
                            $model->customer_code = !empty($model->customer_code) ? $model->customer_code : $model->dcs_code;
                        }
                        $model->from_date = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : '';
                        $model->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : '';
                    } else {
                        $model = new TblBulkDataImport();
                        $model->attributes = $data;
                        $model->uuid = $uuid;
                        $model->union_code = $row->union_code;
                        $model->route_code = !empty($model->route_code) ? $model->route_code : NULL;
                        $FileType = ['milk_collection_dpu_data', 'milk_collection_other_data'];
                        if (in_array($row->file_type, $FileType)) {
                            $model->SetDataForShagunDPU();
                        } else {
                            $model->shift_code = (strtoupper($model->shift_code) == 'M') ? 1 : 2;
                            $model->own_bmc_code = !empty($model->own_bmc_code) ? $model->own_bmc_code : $model->bmc_code;
                        }
                        $DefaultSampleNo = ['sample_milk_collection'];
                        if (in_array($row->file_type, $DefaultSampleNo)) {
                            $model->sample_no = 0;
                        }
                        $model->date_time_of_collection = !empty($model->date_time_of_collection) ? date('Y-m-d', strtotime($model->date_time_of_collection)) : '';
                        $model->date_time_of_collection = $model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($model->shift_code);
                    }
                    if ($model->save()) {
                        $success++;
                    } else {
                        $data['response_msg'] = 'File Record error.';
                        $error_lines[] = $data;
                    }
                }
                $sp_param = [];
                $sp_param[] = $uuid;
                $sp_param[] = $row->created_by;
                $sp_param[] = $row->union_code;
                $sp_result = [];
                $success_sp_result = [];
                if ($success > 0) {
                    \Yii::$app->general->getSpData($sp_name, $sp_param, TRUE);
                    $success_sp_result = \Yii::$app->general->getSpData($sp_name . '_ErrorList', [$uuid, 'SuccessList']);
                    $sp_result = \Yii::$app->general->getSpData($sp_name . '_ErrorList', [$uuid, 'ErrorList']);
                }
                $error_lines = array_merge($sp_result, $error_lines);
                $filePath = NULL;
                $successfilePath = NULL;
                if (!empty($error_lines)) {
                    $column_header = array_keys($error_lines[0]);
                    $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web/bulkdata/' . $row->file_type . '/archive/';
                    if (Yii::$app->general->checkDirectory($path)) {
                        $objPHPExcel = new Spreadsheet();
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
                        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
                        $objWriter->save($filePath);
                        copy($row->file_path, $path . $row->file_name);
                        unlink($row->file_path);
                        $filePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'error_' . $row->file_name;
                    }
                }
                if (!empty($success_sp_result)) {
                    $column_header = array_keys($success_sp_result[0]);
                    $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web/bulkdata/' . $row->file_type . '/archive/';
                    if (Yii::$app->general->checkDirectory($path)) {
                        $objPHPExcel = new Spreadsheet();
                        $sheet = $objPHPExcel->getActiveSheet();
                        $sheet->fromArray(
                                $column_header, // The data to set
                                NULL, // Array values with this value will not be set
                                'A1'         // Top left coordinate of the worksheet range where
                                //    we want to set these values (default is A1)
                        );
                        $sheet->fromArray(
                                $success_sp_result, // The data to set
                                NULL, // Array values with this value will not be set
                                'A2'         // Top left coordinate of the worksheet range where
                                //    we want to set these values (default is A1)
                        );
                        $successfilePath = $path . 'success_' . $row->file_name;
                        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
                        $objWriter->save($successfilePath);
                        $successfilePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'success_' . $row->file_name;
                    }
                }
                $row->total_count = $total_cnt;
                $row->error_count = count($error_lines);
                $row->success_count = $row->total_count - $row->error_count;
                $row->status = 2;
                $row->response_datetime = date('Y-m-d H:i:s');
                $row->response_msg = 'File Processed';
                $row->error_file_path = $filePath;
                $row->success_file_path = $successfilePath;
                $row->save(FALSE);
            } else {
                $row->status = 3;
                $row->response_msg = 'Import Config Missing.';
                $row->response_datetime = date('Y-m-d H:i:s');
                $row->save(FALSE);
            }
        } catch (\Throwable $ex) {
            $row->status = 3;
            $row->response_msg = 'Unable to read file.';
            $row->response_datetime = date('Y-m-d H:i:s');
            $row->save(FALSE);
        }
    }

}
