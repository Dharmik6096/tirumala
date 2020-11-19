<?php

namespace app\modules\bkgprocess\controllers;

use yii\web\Controller;
use yii;
use app\modules\bkgprocess\models\FtpGenerate;
use yii\data\ArrayDataProvider;
use PHPExcel;
use app\components\FTPConnection;
use app\modules\bkgprocess\models\TblFtpDetail;

/**
 * Default controller for the `bkgprocess` module
 */
class FtpGenerateController extends \app\controllers\ChildController {

    private $data = [], $type = 'html', $output = '', $report = '', $dataProvider = '', $status;

    public function actionIndex() {
        $model = new FtpGenerate();
        $command = Yii::$app->getDb()->createCommand('SELECT NEWID() as id')->queryOne();
        $model->token = $command['id'];
        if ($this->report != '') {
            $this->data = $this->getLabels($this->report);
            if (!empty($this->data['scenario'])) {
                $model->scenario = $this->data['scenario'];
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->request->post('submit') == 'ftp-submit') {
                $model->status = 'Force Generate';
            }
            $this->LoadReport($model);
            $model->status = 'Generate';
            if (Yii::$app->request->post('submit') == 'ftp-submit') {
                
            } else if (empty($this->output)) {
                $this->output = Yii::t('app', 'No Data Available.');
            }
        }
        return $this->render('index', ['result' => $this->output, 'report' => $this->report, 'data' => $this->data, 'model' => $model, 'dataProvider' => $this->dataProvider]);
    }

    public function actionFtpMilkCollection() {
        $this->report = 'FTPMilkCollection';
        return $this->actionIndex();
    }

    private function LoadReport($model) {
        if (empty($model->union_code)) {
            $model->union_code = !empty(Yii::$app->session->get('organizations_code')) ? ',' . Yii::$app->session->get('organizations_code') . ',' : 0;
        }
        if (empty($model->plant_code)) {
            $model->plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
        }
        if (empty($model->mcc_code)) {
            $model->mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
        }
        if (empty($model->bmc_code)) {
            $model->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
        }
        $controls = [];
        $param = explode(',', $this->data['param']);
        foreach ($param as $key => $value) {
            $value_array = explode(':', $value);
            $value = $value_array[0];
            if (isset($value_array[1]) && $value_array[1] == 'string') {
                $model->{$value} = !empty($model->{$value}) ? date('Y-m-d', strtotime($model->{$value})) : date('Y-m-d');
                if (isset($value_array[2])) {
                    $shift = !empty($model->{$value_array[2]}) ? \Yii::$app->general->getshift($model->{$value_array[2]}) : '00:00:00';
                    $model->{$value} .= ' ' . $shift . '.000';
                }
            }
            $controls[$value] = $model->{$value};
        }

        $sp_name = $this->data['sp_name'];
        $output = \Yii::$app->general->getSpData($sp_name, $controls);

        $this->output = $output;
        if (!empty($output)) {
            if (Yii::$app->request->post('submit') == 'ftp-submit') {
                $this->GenerateFileFTP($output, $model);
                $this->output = [];
            } else {
                $attr = '';
                foreach ($output[0] as $att => $value) {
                    if (!isset($this->data['select_only']) || !in_array($att, $this->data['select_only'])) {
                        $attr .= "'" . $att . "',";
                    }
                }
                $this->dataProvider = new ArrayDataProvider([
                    'allModels' => $output,
                    'pagination' => false,
                    'sort' => [
                        'defaultOrder' => [],
                        'attributes' => [
                            $attr
                        ],
                    ],
                ]);
            }
        }
    }

    private function GenerateFileFTP($output, $Ftpmodel) {
        $success = 0;
        $error = 0;
        $cnt = 0;
        $connection = FALSE;
        $text = '';

        if (!empty($output)) {
            $keydata = array_keys($output[0]);
            $txtrowA = implode(',', $keydata);
            $text .= $txtrowA . PHP_EOL;
        }
        foreach ($output as $rows) {
            $txtrowA = implode(',', $rows);
            $text .= $txtrowA . PHP_EOL;
        }
        if ($text != '') {
            $local_path = Yii::$app->basePath . '/web/FtpUpload/';
            Yii::$app->general->checkDirectory($local_path);
            $file_name = date('YmdHis') . ".csv";
            $fileName = $local_path . $file_name;
            $vfile = fopen($fileName, "w") or die("Unable to open file!");
            if (fwrite($vfile, $text)) {
                $data_write = TRUE;
                fclose($vfile);
            } else {
                fclose($vfile);
                unlink($vfile);
            }
            try {
                $ftp_model = new TblFtpDetail();
                $ftp_model->ftp_connection_code = $Ftpmodel->mcc_code;
                $ftpData = $ftp_model->getData();
                if ($connection) {
                    $ftp->CloseConnection();
                }
                $ftp = new FTPConnection();
                $ftp->ftp_type = $ftpData->ftp_type;
                $ftp->ftp_host = $ftpData->ftp_host;
                $ftp->ftp_username = $ftpData->ftp_username;
                $ftp->ftp_password = $ftpData->ftp_password;
                $ftp->ftp_port = $ftpData->ftp_port;
                $ftp->conn_init = FALSE;
                $ftp->conn_close = FALSE;
                $ftp->make_dir = FALSE;
                $ftp->ftp_pasv = false;
                $connection = $ftp->ConnectServer();

                if ($connection) {
                    $local_path = str_replace('\\', '/', $local_path);
                    $ftp->ftp_path = $ftpData->ftp_path;
                    $ftp->local_path = $local_path;
                    $ftp->file_name = $file_name;
                    if ($ftp->UploadFile()) {
                        $spparam = [];
                        $spname = 'mis_ftp_vijaya_milk_collection_update_status';
                        $spparam[] = $Ftpmodel->token;
                        \Yii::$app->general->getSpData($spname, $spparam, TRUE);
                        $msg = 'File Uploaded Succsessfully';
                    }
                }
            } catch (\yii\db\Exception $e) {
                $msg = 'File Not Uploaded';
            }
        }

        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
            'message' => $msg]);
    }

    /* Reports Configuration */

    private function getLabels($l) {
        $label = [
            'FTPMilkCollection' => [
                'param' => 'union_code,plant_code,mcc_code,bmc_code,from_date:string:from_shift,to_date:string:to_shift,status:hidden,token:hidden',
                'sp_name' => 'mis_ftp_vijaya_milk_collection',
                'scenario' => 'FTPMilkCollection',
                'title' => 'FTP File Upload',
            ],
        ];
        return $label[$l];
    }

}
