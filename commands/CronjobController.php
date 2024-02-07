<?php

namespace app\commands;

use Yii;
use app\modules\configuration\models\TblReportTxnLog;
use PHPExcel;
use yii\helpers\Url;
use Jaspersoft\Client\Client;

class CronjobController extends \yii\console\Controller {

    public $report_folder_main = '/web/export_report/';
    public $report_folder = '';
    public $report_path = '';
    public $output = '';
    public $model = '';

    public function actionReportGenerate() {
        $report_path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . $this->report_folder_main;
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

                        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP CALL');
                        $this->output = \Yii::$app->general->getSpData($this->model->sp_name, $controls);
                        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SP Result');
                        if (!empty($this->output)) {
                            $this->SaveExcel();
                            $status = 2;
                            $msg = 'Report Generated.';
                        } else {
                            $status = 2;
                            $msg = 'No Data Found.';
                        }
                    } else {
                        $clientJasper = new Client(\Yii::$app->params['jasper_server'], \Yii::$app->params['jasper_username'], \Yii::$app->params['jasper_password']);
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
                var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . ' Error occurred: ' . $ex->getMessage());
                $this->model->status = 3;
                $this->model->response_msg = 'Unable to Generate Report.';
                $this->model->updated_at = $this->model->response_datetime = date('Y-m-d H:i:s');
                $this->model->save();
            }
        }
    }

    public function SaveExcel() {
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Start');
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
        ];
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $file_header = !empty($this->output) ? array_keys($this->output[0]) : [];

        $dataToDecrypt = !empty($this->model->decrypt_data) ? json_decode($this->model->decrypt_data, TRUE) : [];
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
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Decrypted');

        $sheet->fromArray($file_header, NULL, 'A1');
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Header');

        $sheet->fromArray($this->output, NULL, 'A2');
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel excel Data');

        $labelArray = !empty($this->output) ? array_keys($this->output[0]) : [];
        $labelT = date('YmdHis') . '_' . $this->model->user_code . '_' . $this->model->report_txn_log_id . '_' . $this->model->report_title;
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel header before save');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        $objWriter->save($this->report_path . $fileName);
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel filesave');
        
        $this->model->file_name = $fileName;
        $this->model->file_path = $this->report_folder . $fileName;
        var_dump(date('YmdHis') . 'report_txn_log_id=' . $this->model->report_txn_log_id . 'MIS SaveExcel Done');
    }

    public function SaveJasperPdf() {
        $fileName = date('YmdHis') . '_' . $this->model->user_code . '_' . $this->model->report_txn_log_id . '_' . $this->model->report_title . '.pdf';
        file_put_contents($this->report_path . $fileName, $this->output);
        $this->model->file_name = $fileName;
        $this->model->file_path = $this->report_folder . $fileName;
    }

}
