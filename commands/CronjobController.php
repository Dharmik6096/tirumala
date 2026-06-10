<?php

namespace app\commands;

use app\components\Worksheet;
use Yii;
use app\modules\configuration\models\TblReportTxnLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\helpers\Url;
use Jaspersoft\Client\Client;

class CronjobController extends \yii\console\Controller {

    public function actionReportGenerate() {
        $report_folder_main = '/export_report/';
        $report_path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web' . $report_folder_main;

        Yii::$app->general->checkDirectory($report_path);
        Yii::$app->general->checkDirectory($report_path . '/mis/');
        Yii::$app->general->checkDirectory($report_path . '/jasper/');
        while (true) {
            $model = TblReportTxnLog::find()->where(['status' => 0])->orderBy(['report_txn_log_id' => SORT_ASC])->one();
            if (empty($model)) {
                sleep(30);
                unset($model);
                continue;
            }
            sleep(2);
            try {
                $report_folder = $report_folder_main . $model->report_type . '/';
                $report_path_type = $report_path . $model->report_type . '/';

                $model->status = 1;
                $model->updated_at = $model->pick_datetime = $model->cron_pick_datetime = date('Y-m-d H:i:s');
                $model->save();
                $status = 3;
                $msg = 'Error While Report Generate.';
                $controls = json_decode($model->input_param, TRUE);
                if ($model->report_type == 'mis') {

                    //              var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP CALL');
                    $output = \Yii::$app->general->getSpData($model->sp_name, $controls);
                    //               var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP Result');
                    if (!empty($output)) {
                        $result = $this->SaveExcel($model, $output, $report_path_type, $report_folder);
                        if ($result === TRUE) {
                            $status = 2;
                            $msg = 'Report Generated.';
                        } else {
                            $status = 3;
                            $msg = $result;
                        }
                    } else {
                        $status = 2;
                        $msg = 'No Data Found.';
                    }
                    unset($output);
                } else {
                    $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
                    $clientJasper->setRequestTimeout(600);
                    $output = $clientJasper->reportService()->runReport(\Yii::$app->params['report_path'] . $model->sp_name, 'pdf', null, null, $controls);
                    $this->SaveJasperPdf($model, $output, $report_path_type, $report_folder);
                    $status = 2;
                    $msg = 'Report Generated.';
                    unset($output, $clientJasper);
                }
                $model->status = $status;
                $model->response_msg = $msg;
                $model->updated_at = $model->response_datetime = date('Y-m-d H:i:s');
                $model->save();
            } catch (\Throwable $ex) {
                //     var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . ' Error occurred: ' . $ex->getMessage());
                $msg = substr($ex->getMessage(), 0, 254);
                $model->status = 3;
                $model->response_msg = $msg;
                $model->updated_at = $model->response_datetime = date('Y-m-d H:i:s');
                $model->save();
            }
            unset($model, $controls, $report_folder, $report_path_type, $status, $msg);
        }
    }

    public function SaveExcel($model, $output, $report_path, $report_folder) {
        $chunk_size = 1000;
        $chunk_limit = 100;
        $sheet_change_on_chunk = 251;

        $record_limit = ($chunk_limit * $chunk_size);
        if (count($output) > $record_limit) {
            return 'More than ' . $record_limit . ' Records.Please Change Your Filter.';
        }
        //   var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Start');
        $header = [
            'mime' => 'application/vnd.ms-excel',
            'extension' => 'xlsx',
            'writer' => IOFactory::WRITER_XLSX,
        ];
        $objPHPExcel = new Spreadsheet();
        $file_header = !empty($output) ? array_keys($output[0]) : [];
        foreach ($file_header as $key => $value) {
            $file_header[$key] = \Yii::t('app', $value);
        }
        $dataToDecrypt = !empty($model->decrypt_data) ? json_decode($model->decrypt_data, TRUE) : [];
        // $decrypt_data = !empty($this->model->decrypt_data) ? json_decode($this->model->decrypt_data, TRUE) : [];
        // $dataToDecrypt = [];
        // $dataToText = [];
        // if (!empty($decrypt_data)) {
        //     $dataToDecrypt = !empty($decrypt_data['to_decrypt']) ? json_decode($decrypt_data['to_decrypt'], TRUE) : [];
        //     $dataToText = !empty($decrypt_data['to_text']) ? json_decode($decrypt_data['to_text'], TRUE) : [];
        // }
        $dataToDecryptCheck = false;
        foreach ($output[0] as $att => $value) {
            if (!$dataToDecryptCheck && !empty($dataToDecrypt) && in_array($att, $dataToDecrypt)) {
                $dataToDecryptCheck = true;
            }
        }
        if ($dataToDecryptCheck && !empty($dataToDecrypt)) {
            for ($i = 0; $i < count($output); $i++) {
                foreach ($dataToDecrypt as $decKey) {
                    if (!empty($output[$i]) && !empty($output[$i][$decKey])) {
                        $output[$i][$decKey] = Yii::$app->general->decryptData($output[$i][$decKey]) !== FALSE ? Yii::$app->general->decryptData($output[$i][$decKey]) : $output[$i][$decKey];
                    }
                }
            }
        }
        //  var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Decrypted');

        $output_chunk = array_chunk($output, $chunk_size, TRUE);
        $chunk_count = count($output_chunk);

        $a = 1;
        $sheet_no = 2;
        //   var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Sheet Count ' . count($chunk_count));
        //    var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Data Count ' . count($this->output));
        // $sheet = $objPHPExcel->getActiveSheet();
        // $sheet->setTitle('Sheet1');
        $customWorksheet = new Worksheet($objPHPExcel, 'Sheet1');
        $objPHPExcel->addSheet($customWorksheet);
        $objPHPExcel->removeSheetByIndex(0);

        $header_info = !empty($model->search_param) ? json_decode($model->search_param, true) : [];
        $header_included = !empty($header_info['header_included']) ? $header_info['header_included'] : false;
        $username = !empty($header_info['username']) ? $header_info['username'] : 'Not Available';
        $PrintedOnDateTime = !empty($model->created_at) ? date('d-m-Y H:i:s', strtotime($model->created_at)) : date('d-m-Y H:i:s');

        $header_rows = 1;
        if ($header_included) {
            $colCount = count($file_header);
            $lastCol = ($colCount > 0) ? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount) : 'A';
            $header_rows = 4;
            $companyName = isset($header_info['organization_name']) ? $header_info['organization_name'] : 'Everest Instruments Pvt. Ltd.';
            $reportTitle = $model->report_title;
            $searchParams = isset($header_info['search_params']) ? $header_info['search_params'] : '';

            $customWorksheet->setCellValue('A1', $companyName);
            $customWorksheet->setCellValue('A2', $reportTitle);
            $customWorksheet->setCellValue('A3', $searchParams);
            $customWorksheet->mergeCells("A1:{$lastCol}1");
            $customWorksheet->mergeCells("A2:{$lastCol}2");
            $customWorksheet->mergeCells("A3:{$lastCol}3");
            $customWorksheet->getStyle("A1:{$lastCol}3")->getFont()->setBold(true);
            $customWorksheet->getStyle("A1:{$lastCol}2")->getFont()->setSize(14);
            $customWorksheet->getStyle("A1:{$lastCol}3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $customWorksheet->fromArray($file_header, NULL, 'A' . $header_rows);
        if ($header_included) {
            $customWorksheet->getStyle("A{$header_rows}:{$lastCol}{$header_rows}")->getFont()->setBold(true);
        }

        foreach ($output_chunk as $output) {
            if ($a == $sheet_change_on_chunk) {
                $a = 1;
                $customWorksheet = new Worksheet($objPHPExcel, 'Sheet' . $sheet_no);
                $objPHPExcel->addSheet($customWorksheet);
                $customWorksheet->fromArray($file_header, NULL, 'A' . $header_rows);
                if ($header_included) {
                    $customWorksheet->getStyle("A{$header_rows}:{$lastCol}{$header_rows}")->getFont()->setBold(true);
                }
                $sheet_no++;
            }
            $current_row = ($a == 1 ? ($header_rows + 1) : ((($a - 1) * $chunk_size) + ($header_rows + 1)));
            $data_cell = 'A' . $current_row;
            $customWorksheet->fromArray($output, NULL, $data_cell);
            //        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Data Chunk ' . $a);
            $a++;
        }
        //  var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Data written');
        
        $multiple_sheet = !empty($header_info['multiple_sheet']) ? $header_info['multiple_sheet'] : null;
        if (!empty($multiple_sheet)) {
            $controls = json_decode($model->input_param, TRUE);
            foreach ($multiple_sheet as $new_sheet_name => $new_sp_name) {
                $newsheet = $objPHPExcel->createSheet($sheet_no);
                $newsheet->setTitle($new_sheet_name);
                $newoutput = \Yii::$app->general->getSpData($new_sp_name, $controls);
                $new_file_header = !empty($newoutput) ? array_keys($newoutput[0]) : [];
                foreach ($new_file_header as $key => $value) {
                    $new_file_header[$key] = \Yii::t('app', $value);
                }
                
                $new_header_rows = 1;
                if ($header_included) {
                    $newColCount = count($new_file_header);
                    $newLastCol = ($newColCount > 0) ? \PHPExcel_Cell::stringFromColumnIndex($newColCount - 1) : 'A';
                    $new_header_rows = 5;
                    $newLastColBefore = ($newColCount > 1) ? \PHPExcel_Cell::stringFromColumnIndex($newColCount - 2) : 'A';

                    $newsheet->setCellValue('A1', isset($companyName) ? $companyName : '');
                    $newsheet->setCellValue('A3', isset($reportTitle) ? $reportTitle : '');
                    $newsheet->setCellValue('A4', isset($searchParams) ? $searchParams : '');
                    $newsheet->setCellValue($newLastCol . '1', 'Username : ' . $username);
                    $newsheet->setCellValue($newLastCol . '2', 'Printed on : ' . $PrintedOnDateTime);

                    $newsheet->mergeCells("A1:{$newLastColBefore}2");
                    $newsheet->mergeCells("A3:{$newLastCol}3");
                    $newsheet->mergeCells("A4:{$newLastCol}4");

                    $newsheet->getStyle("A1:{$newLastColBefore}2")->getFont()->setBold(true);
                    $newsheet->getStyle("A1:{$newLastColBefore}2")->getFont()->setSize(14);
                    $newsheet->getStyle("A1:{$newLastColBefore}2")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $newsheet->getStyle("A1:{$newLastColBefore}2")->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $newsheet->getStyle("A3:{$newLastCol}4")->getFont()->setBold(true);
                    $newsheet->getStyle("A3:{$newLastCol}3")->getFont()->setSize(14);
                    $newsheet->getStyle("A3:{$newLastCol}4")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $newsheet->getStyle($newLastCol . '1:' . $newLastCol . '2')->getFont()->setBold(true);
                    $newsheet->getStyle($newLastCol . '1:' . $newLastCol . '2')->getFont()->setSize(10);
                    $newsheet->getStyle($newLastCol . '1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $newsheet->getStyle($newLastCol . '2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $newsheet->getStyle($newLastCol . '1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $newsheet->getStyle($newLastCol . '2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                }

                $newsheet->fromArray($new_file_header, NULL, 'A' . $new_header_rows);
                if (!empty($newoutput)) {
                    $newsheet->fromArray($newoutput, NULL, 'A' . ($new_header_rows + 1));
                }

                if ($header_included && isset($newLastCol)) {
                    $newsheet->getStyle("A{$new_header_rows}:{$newLastCol}{$new_header_rows}")->getFont()->setBold(true);
                }
                $sheet_no++;
            }
        }
        //  $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $labelT = date('YmdHis') . '_' . $model->user_code . '_' . $model->report_txn_log_id . '_' . str_replace('/', '_', $model->report_title);
        $fileName = $labelT . '.' . $header['extension'];

        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
        $objWriter->save($report_path . $fileName);

        //      var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Done');

        $model->file_name = $fileName;
        $model->file_path = $report_folder . $fileName;
        unset($objPHPExcel, $objWriter, $output, $file_header, $dataToDecrypt, $output_chunk);
        return TRUE;
    }

    public function SaveJasperPdf($model, $output, $report_path, $report_folder) {
        $fileName = date('YmdHis') . '_' . $model->user_code . '_' . $model->report_txn_log_id . '_' . str_replace('/', '_', $model->report_title) . '.pdf';
        file_put_contents($report_path . $fileName, $output);
        $model->file_name = $fileName;
        $model->file_path = $report_folder . $fileName;
        unset($fileName);
    }

}
