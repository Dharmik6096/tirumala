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
        $report_folder_main = '/web/export_report/';

        $report_path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . $report_folder_main;



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

        $customWorksheet->fromArray($file_header, NULL, 'A1');
        $customWorksheet->fromArray($output, NULL, 'A2');
        // $sheet->fromArray($file_header, NULL, 'A1');
        // if (!empty($dataToText)) {
        //     foreach ($dataToText as $columnName) {
        //         $columnIndex = array_search($columnName, $file_header);
        //         if ($columnIndex !== false) {
        //             $accountNoColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
        //             $sheet->getStyle($accountNoColumn)
        //                     ->getNumberFormat()
        //                     ->setFormatCode('00000000000');
        //         }
        //     }
        // }
        //     var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Header Sheet1');
        foreach ($output_chunk as $output) {
            if ($a == $sheet_change_on_chunk) {
                $a = 1;
                $customWorksheet = new Worksheet($objPHPExcel, 'Sheet' . $sheet_no);
                $objPHPExcel->addSheet($customWorksheet);
                $customWorksheet->fromArray($file_header, null, 'A1');
                // $sheet = $objPHPExcel->createSheet($sheet_no); // Pass the index as the second argument
                // $sheet->setTitle('Sheet' . $sheet_no);
                // $sheet->fromArray($file_header, NULL, 'A1');
                // if (!empty($dataToText)) {
                //     foreach ($dataToText as $columnName) {
                //         $columnIndex = array_search($columnName, $file_header);
                //         if ($columnIndex !== false) {
                //             $accountNoColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                //             $sheet->getStyle($accountNoColumn)
                //                     ->getNumberFormat()
                //                     ->setFormatCode('00000000000');
                //         }
                //     }
                // }
                //       var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Header Sheet' . $sheet_no);
                $sheet_no++;
            }
            $data_cell = 'A' . ($a == 1 ? '2' : ((($a - 1) * $chunk_size) + 2));
            $customWorksheet->fromArray($output, NULL, $data_cell);
            //        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Data Chunk ' . $a);
            $a++;
        }
        //  var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Data written');
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
