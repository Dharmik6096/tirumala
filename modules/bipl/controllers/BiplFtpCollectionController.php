<?php

namespace app\modules\bipl\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\ChildController;
use app\modules\bipl\models\BiplFtpCollection;
use app\modules\collection\models\TblProcessedFiles;
use yii\helpers\Url;

/**
 * Default controller for the `bipl` module
 */
class BiplFtpCollectionController extends ChildController {

    var $path = 'C:/BIPLFTP/EKOMILK';
    var $errorPath = 'C:/BIPLFTP/ErrorLogs/FTP';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionAddFtpData() {
        if (file_exists($this->path) && is_dir($this->path)) {
            $directories = glob($this->path . '/*', GLOB_ONLYDIR);
            $process_success = 0;
            foreach ($directories as $dir) {
                $check = !empty(glob($dir . '/RIPFILES', GLOB_ONLYDIR));
                $files = array_filter(glob($dir . '/RIPFILES/*.RIP'), 'is_file');
                if ($check && !empty($files)) {
                    $cp_code = str_replace($this->path . '/', '', $dir);
                    $processed = new TblProcessedFiles();
                    $processed = $processed->getProcessedFiles('bipl', $cp_code);
                    $to_process = array_diff($files, preg_filter('/^/', $dir . '/RIPFILES/', $processed));
                    foreach ($to_process as $datafile) {
                        $data = $this->prepareDataFromFile($datafile, $cp_code);
                        //var_dump($data); exit;
                        $transaction = $this->generalModel->saveTransaction([$data[0]], $data[1], ['ftp collection', 'create']);
                        if ($transaction !== FALSE) {
                            $process_success++;
                        }
                        if (!empty($data[2])) {
                            $log_dir = Yii::$app->general->checkDirectory($this->errorPath);
                            if ($log_dir) {
                                $text = json_encode($data[2]);
                                $file_name = str_replace('.RIP', '', str_replace($dir . '/RIPFILES/', '', $datafile));
                                Yii::$app->general->createLogFile($this->errorPath, $text, $file_name, $cp_code);
                            }
                        }
                    }
                }
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => $process_success . ' files processed successfully']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'The ftp directory does not exist.']);
        }
        return $this->redirect(Url::previous());
    }

    protected function prepareDataFromFile($datafile, $cp_code) {
        if (!empty($datafile) && !empty($datafile)) {
            $codes = \app\modules\organisation\models\TblSocietyCodes::find()->select(['dcs_code', 'union_code'])->where(['bipl_code' => $cp_code])->one();
            $fileModel = new TblProcessedFiles();
            $fileModel->vendor_id = 'BIPL';
            $fileModel->file_path = str_replace($this->path . '/' . $cp_code . '/RIPFILES/', '', $datafile);
            $fileModel->cp_code = $cp_code;
            $fileModel->processed_at = date('Y-m-d H:i:s');
            $fileModel->process_id = Yii::$app->general->getCodeAutoIncrement($fileModel);
            $fileModel->dcs_code = !empty($codes) ? $codes->dcs_code : null;
            $fileModel->union_code = !empty($codes) ? $codes->union_code : null;
            fopen($datafile, "r");
            $fileData = file($datafile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $count = count($fileData);
            $collection = [];
            $invalid = [];
            if ($count != 0) {
                $fields = explode(",", strtolower($fileData[0]));
                $fields[0] = 'id';
                foreach ($fileData as $key => $fd) {
                    if ($key != 0 && strpos($fd, 'Total Number of Records') === false) {
                        if (count($fields) == count(explode(",", $fd))) {
                            $data = array_combine($fields, explode(",", $fd));
                            $collectionModel = new BiplFtpCollection();
                            $collectionModel->scenario = 'checkDate';
                            $collectionModel->attributes = $data;
                            $collectionModel->process_id = $fileModel->process_id;
                            if (empty($collectionModel->cp_code))
                                $collectionModel->cp_code = $cp_code;
                            if ($collectionModel->validate()) {
                                $collectionModel->date = Yii::$app->formatter->asDate($collectionModel->date, DATE_FORMAT);
                                $collectionModel->scenario = 'default';
                                $collectionModel->local_code = str_pad((int) $collectionModel->local_code, 4, '0', STR_PAD_LEFT);
                                array_push($collection, $collectionModel);
                            } else {
                                $invalid[$key] = $collectionModel->getErrors();
                            }
                        } else {
                            $invalid[$key] = 'Number of record values does not match number of fileds.';
                        }
                    }
                }
            }
            return [$fileModel, $collection, $invalid];
        }
        return false;
    }

}
