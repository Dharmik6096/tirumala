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

    public $report_folder_main = '/export_report/';
    public $report_folder = '';
    public $report_path = '';
    public $output = '';
    public $model = '';

    public function actionReportGenerate() {
        $report_path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web' . $this->report_folder_main;
        Yii::$app->general->checkDirectory($report_path);
        Yii::$app->general->checkDirectory($report_path . '/mis/');
        Yii::$app->general->checkDirectory($report_path . '/jasper/');
        $i = 0;
        while ($i < 1) {
            sleep(2);
            try {
                $this->model = new TblReportTxnLog();
                $this->model = $this->model->find()->where(['status' => 0])->orderBy(['report_txn_log_id' => SORT_ASC])->one();
                if (!empty($this->model)) {
                    $this->report_folder = $this->report_folder_main . $this->model->report_type . '/';
                    $this->report_path = $report_path . $this->model->report_type . '/';
                    $this->model->status = 1;
                    $this->model->updated_at = $this->model->pick_datetime = $this->model->cron_pick_datetime = date('Y-m-d H:i:s');
                    $this->model->save();
                    $status = 3;
                    $msg = 'Error While Report Generate.';
                    $controls = json_decode($this->model->input_param, TRUE);
                    if ($this->model->report_type == 'mis') {

                        //              var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP CALL');
                        $this->output = \Yii::$app->general->getSpData($this->model->sp_name, $controls);
                        //               var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP Result');
                        if (!empty($this->output)) {
                            $result = $this->SaveExcel();
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
                    } else {
                        $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
                        $clientJasper->setRequestTimeout(600);
                        $this->output = $clientJasper->reportService()->runReport(\Yii::$app->params['report_path'] . $this->model->sp_name, 'pdf', null, null, $controls);
                        $this->SaveJasperPdf();
                        $status = 2;
                        $msg = 'Report Generated.';
                    }
                    $this->model->status = $status;
                    $this->model->response_msg = $msg;
                    $this->model->updated_at = $this->model->response_datetime = date('Y-m-d H:i:s');
                    $this->model->save();
                }
            } catch (\Throwable $ex) {
                //     var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . ' Error occurred: ' . $ex->getMessage());
                $msg = substr($ex->getMessage(), 0, 254);
                $this->model->status = 3;
                $this->model->response_msg = $msg;
                $this->model->updated_at = $this->model->response_datetime = date('Y-m-d H:i:s');
                $this->model->save();
            }
        }
    }

    public function SaveExcel() {
        $chunk_size = 1000;
        $chunk_limit = 100;
        $sheet_change_on_chunk = 251;

        $record_limit = ($chunk_limit * $chunk_size);
        if (count($this->output) > $record_limit) {
            return 'More than ' . $record_limit . ' Records.Please Change Your Filter.';
        }
        //   var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Start');
        $header = [
            'mime' => 'application/vnd.ms-excel',
            'extension' => 'xlsx',
            'writer' => IOFactory::WRITER_XLSX,
        ];
        $objPHPExcel = new Spreadsheet();
        $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];

        $dataToDecrypt = !empty($this->model->decrypt_data) ? json_decode($this->model->decrypt_data, TRUE) : [];
        // $decrypt_data = !empty($this->model->decrypt_data) ? json_decode($this->model->decrypt_data, TRUE) : [];
        // $dataToDecrypt = [];
        // $dataToText = [];
        // if (!empty($decrypt_data)) {
        //     $dataToDecrypt = !empty($decrypt_data['to_decrypt']) ? json_decode($decrypt_data['to_decrypt'], TRUE) : [];
        //     $dataToText = !empty($decrypt_data['to_text']) ? json_decode($decrypt_data['to_text'], TRUE) : [];
        // }
        $dataToDecryptCheck = false;
        foreach ($this->output[0] as $att => $value) {
            if (!$dataToDecryptCheck && !empty($dataToDecrypt) && in_array($att, $dataToDecrypt)) {
                $dataToDecryptCheck = true;
            }
        }
        if ($dataToDecryptCheck && !empty($dataToDecrypt)) {
            for ($i = 0; $i < count($this->output); $i++) {
                foreach ($dataToDecrypt as $decKey) {
                    if (!empty($this->output[$i]) && !empty($this->output[$i][$decKey])) {
                        $this->output[$i][$decKey] = Yii::$app->general->decryptData($this->output[$i][$decKey]) !== FALSE ? Yii::$app->general->decryptData($this->output[$i][$decKey]) : $this->output[$i][$decKey];
                    }
                }
            }
        }
        //  var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Decrypted');

        $output_chunk = array_chunk($this->output, $chunk_size, TRUE);
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
        $customWorksheet->fromArray($this->output, NULL, 'A2');
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
        $labelT = date('YmdHis') . '_' . $this->model->user_code . '_' . $this->model->report_txn_log_id . '_' . str_replace('/', '_', $this->model->report_title);
        $fileName = $labelT . '.' . $header['extension'];

        $objWriter = IOFactory::createWriter($objPHPExcel, $header['writer']);
        $objWriter->save($this->report_path . $fileName);

        //      var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Done');

        $this->model->file_name = $fileName;
        $this->model->file_path = $this->report_folder . $fileName;

        return TRUE;
    }

    public function SaveJasperPdf() {
        $fileName = date('YmdHis') . '_' . $this->model->user_code . '_' . $this->model->report_txn_log_id . '_' . str_replace('/', '_', $this->model->report_title) . '.pdf';
        file_put_contents($this->report_path . $fileName, $this->output);
        $this->model->file_name = $fileName;
        $this->model->file_path = $this->report_folder . $fileName;
    }

}
