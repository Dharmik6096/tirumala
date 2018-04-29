<?php

namespace app\modules\restservices\models;

use Yii;
use ReflectionClass;
use app\models\GeneralModel;
use DateTime;
use app\modules\stellapps\models\TmccConfig;
use app\modules\stellapps\models\StellappsMember;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\stellapps\models\StellappsCollection;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateBased;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;

//use app\modules\stellapps\models\StellappsPriceChart;
//use app\modules\stellapps\models\StellappsPriceChartBased;
//use app\modules\stellapps\models\StellappsPriceChartDetail;
//use app\modules\stellapps\models\StellappsPriceChartApplicability;


class StellappsModel {

    private $data, $model, $username = "STA", $password = "STA", $path = 'C:\STELLAPPSFTP\ErrorLogs';
    public $change_att = array('member_code' => 'ex_member_code');

    function __construct() {
        set_error_handler(array($this, 'handleError'));
    }

    public function handleError($code, $message, $file, $line) {
        return;
    }

    public function manipulation($data) {
        $this->data = $data;
        $this->changeAttributeLabel();
        $this->validateTmccCode();
        switch (1) {
//            case empty($this->data['tmcc_code']):
//                return $this->responseData(0, 'tmcc_code');
            case empty($this->data['svc']):
                return $this->responseData(0, 'nosvc');
            case empty($this->data['usr']):
                return $this->responseData(0, 'usr');
            case empty($this->data['pswd']):
                return $this->responseData(0, 'pswd');
//            case empty($this->data['dcs_code']):
//                return $this->responseData(0, 'dcs_code');
            default:break;
        }
        if ($this->data['usr'] == $this->username && $this->data['pswd'] == $this->password) {
            switch (trim($data['svc'])) {
                case 'save_tmcc_configs':return $this->saveTmccConfigs();
                case 'save_tmcc_member_list':return $this->saveTmccMemberList();
                case 'save_tmcc_price_chart':return $this->saveTmccPriceChart();
                case 'save_tmcc_price_chart_mapping':return $this->saveTmccPriceChartMapping();
                case 'save_bmc_milk_collection':return $this->saveBmcCollection();
                default:return $this->responseData(0, 'svc');
            }
        } else {
            return $this->responseData(0, 'noauth');
        }
    }

    private function saveTmccConfigs() {
        $this->model = new TmccConfig();
        return $this->saveData();
    }

    private function saveTmccMemberList() {
        $this->model = new StellappsMember();
        return $this->saveData();
    }

    private function saveTmccPriceChart() {
        $this->model = new TblPurchaseRate();
        return $this->savePriceData();
    }

    private function saveTmccPriceChartMapping() {
        $this->model = new StellappsPriceChartApplicability();
        return $this->saveData();
    }

    private function saveBmcCollection() {
        $this->model = new StellappsCollection();
        return $this->saveData();
    }

    private function saveData() {
        $generalModel = new GeneralModel();
        $saveData = [];
        $reponse = 0;
        $update_label = \app\modules\stellapps\Stellapps::getLabels($this->data['svc']);
        $check_update = !empty($update_label) ? $update_label['allow_update'] : FALSE;
        foreach ($this->data['data'] as $value) {
            $tmp = new ReflectionClass($this->model->className());
            $saveModel = $tmp->newInstanceArgs();
            if ($check_update) {
                $primaryKey = $update_label['update_on'];
                $merge_key = !empty($update_label['merge_key']) ? $this->data[$update_label['merge_key']] : '';
                $data_key = !empty($update_label['data_key']) ? $value[$update_label['data_key']] : '';
                $pkValue = $merge_key . $data_key;
                $check_exist = $saveModel->find()->where([$primaryKey => $pkValue])->one();
                if (!empty($check_exist)) {
                    $saveModel = $check_exist;
                    $model_name = Yii::$app->path->define($update_label['history_model']);
                    $historyModel = new $model_name();
                    Yii::$app->operation->history($saveModel, $historyModel, UPDATE);
                    $saveData[] = $historyModel;
                }
            }
            $saveModel->attributes = $this->data;
            $saveModel->attributes = $value;
            $schema = $saveModel->getTableSchema();
            $valid[] = $saveModel->validate();
            if ($saveModel->validate() != FALSE) {
                foreach ($saveModel->attributes as $key => $a) {
                    $type = $schema->columns[$key]->type;
                    if ($type == 'date' && $a != '') {
                        $dt = new \DateTime($a);
                        $a = $dt->format('Y-m-d\TH:i:s.u');
                        $saveModel->$key = $a;
                        $saveModel->scenario = 'default';
                    }
                }
            }
            $saveData[] = $saveModel;
        }
        if (in_array(FALSE, $valid)) {
            $dir = $this->checkDirectory($this->path);
            if ($dir) {
                $logs = [];
                foreach ($saveData as $smodel) {
                    $logs['data'][] = $smodel->getAttributes();
                    $logs['errors'][] = $smodel->getErrors();
                }
                $text = json_encode($logs);
                $this->createCpLogFile($this->path, $text, $this->data['cp_code']);
            }
            $reponse = 0;
        } else {
            $transaction = $generalModel->saveTransaction($saveData, ['stellapps services', 'create']);
            if ($transaction === FALSE) {
                $reponse = 0;
                $dir = $this->checkDirectory($this->path);
                if ($dir) {
                    $text = 'Error ocuured while saving data!!';
                    $this->createCpLogFile($this->path, $text, $this->data['cp_code']);
                }
            } else {
                $reponse = 1;
            }
        }
        return $this->responseData($reponse, $fileName);
    }

    private function savePriceData() {
        $generalModel = new GeneralModel();
        $saveData = [];
        $reponse = 0;
        $tmp = new ReflectionClass($this->model->className());
        $saveModel = $tmp->newInstanceArgs();
       $saveModel->attributes = $this->data;
        var_dump($saveModel);
        die;
        foreach ($this->data['data'] as $value) {
            $tmp = new ReflectionClass($this->model->className());
            $saveModel = $tmp->newInstanceArgs();
            $saveModel->attributes = $this->data;
            $saveModel->attributes = $value;
            var_dump($saveModel);
            die;
            $schema = $saveModel->getTableSchema();
            $valid[] = $saveModel->validate();
            if ($saveModel->validate() != FALSE) {
                foreach ($saveModel->attributes as $key => $a) {
                    $type = $schema->columns[$key]->type;
                    if ($type == 'date' && $a != '') {
                        $dt = new \DateTime($a);
                        $a = $dt->format('Y-m-d\TH:i:s.u');
                        $saveModel->$key = $a;
                        $saveModel->scenario = 'default';
                    }
                }
            }
            $saveData[] = $saveModel;
        }
        if (in_array(FALSE, $valid)) {
            $dir = $this->checkDirectory($this->path);
            if ($dir) {
                $logs = [];
                foreach ($saveData as $smodel) {
                    $logs['data'][] = $smodel->getAttributes();
                    $logs['errors'][] = $smodel->getErrors();
                }
                $text = json_encode($logs);
                $this->createCpLogFile($this->path, $text, $this->data['cp_code']);
            }
            $reponse = 0;
        } else {
            $transaction = $generalModel->saveTransaction($saveData, ['stellapps services', 'create']);
            if ($transaction === FALSE) {
                $reponse = 0;
                $dir = $this->checkDirectory($this->path);
                if ($dir) {
                    $text = 'Error ocuured while saving data!!';
                    $this->createCpLogFile($this->path, $text, $this->data['cp_code']);
                }
            } else {
                $reponse = 1;
            }
        }
        return $this->responseData($reponse, $fileName);
    }

    private function responseData($status, $error = '') {
        $message = [
            1 => 'Successfully Saved!',
            0 => 'Unable to save!',
        ];
        if (!empty($error) && $status == 0) {
            switch ($error) {
                case 'tmcc_code':
                    $txt = 'tmcc_code can not be blank';
                    break;
                case 'nosvc':
                    $txt = 'svc can not be blank';
                    break;
                case 'svc':
                    $txt = 'invalid svc';
                    break;
                case 'usr':
                    $txt = 'username can not be blank';
                    break;
                case 'pswd':
                    $txt = 'password can not be blank';
                    break;
                case 'noauth':
                    $txt = 'username or password incorrect';
                    break;
                case 'dcs_code':
                    $txt = 'Invalid tmcc_code';
                    break;
                default :
                    $txt = 'unknown error';
            }
            $dir = $this->checkDirectory($this->path);
            if ($dir) {
                $this->createGenLogFile($this->path, $txt);
            }
        }
        return ["status" => $status, "msg" => $message[$status]];
    }

    protected function checkDirectory($path) {
        if (file_exists($path)) {
            if (!is_dir($path)) { //if file is already present, but it's not a dir
                if (mkdir($path, '0755', true) == false) {
                    die('Failed to create folders...' . $path);
                    return false;
                }
            }
        } else { //no file exists with this name
            if (mkdir($path, '0755', true) == false) {
                die('Failed to create folders...' . $path);
                return false;
            }
        }
        return true;
    }

    protected function createCpLogFile($path, $text, $cp_code) {
        $path = $path . '\\' . $cp_code;
        $dir = $this->checkDirectory($path);
        if ($dir) {
            $timestamp = date('d-m-Y-H-i-s');
            $fileName = $path . "\\" . $timestamp . '.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

    protected function createGenLogFile($path, $text) {
        $path = $path . '\general';
        $dir = $this->checkDirectory($path);
        if ($dir) {
            $timestamp = date('d-m-Y-H-i-s');
            $fileName = $path . "\\" . $timestamp . '.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

    private function changeAttributeLabel() {
        foreach ($this->change_att as $key => $value) {
            if (isset($this->data[$key])) {
                $this->data[$value] = $this->data[$key];
                unset($this->data[$key]);
            }
        }
        foreach ($this->data['data'] as $index => $model) {
            foreach ($this->change_att as $key => $value) {
                if (isset($this->data['data'][$index][$key])) {
                    $this->data['data'][$index][$value] = $this->data['data'][$index][$key];
                    unset($this->data['data'][$index][$key]);
                }
            }
        }
    }

    public function validateTmccCode() {
        $dcs_code = NULL;
        if (!empty($this->data['tmcc_code'])) {
            $code = TblSocietyCodes::find()->where(['bipl_code' => $this->data['tmcc_code']])->one();
            if (!empty($code)) {
                $dcs_code = $code->dcs_code;
            }
        }
        $this->data['dcs_code'] = $dcs_code;
    }

}
