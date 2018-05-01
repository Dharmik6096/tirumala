<?php

namespace app\modules\restservices\models;

use Yii;
use ReflectionClass;
use app\models\GeneralModel;
use DateTime;
use app\modules\organisation\models\TblDcsConfig;
use app\modules\stellapps\models\StellappsMember;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\stellapps\models\StellappsCollection;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateBased;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblQualityParam;

class StellappsModel {

    private $data, $model, $username = "STA", $password = "STA", $path = 'C:\STELLAPPSFTP\ErrorLogs';
    public $change_att = array('member_code' => 'ex_member_code', 'rate_id' => 'reference_code');
    public $reference_att = [
        'milk_type' => [
            'model' => 'TblAnimalType',
            'ref_key' => 'animal_type_code',
            'ref_val' => 'animal_type_name',
            'data_mapping' => ['C' => 'COW', 'B' => 'BUFFALO', 'M' => 'MIX'],
        ],
        'rate_type' => [
            'model' => 'TblRateType',
            'ref_key' => 'code',
            'ref_val' => 'rate_type'
        ],
        'shift' => [
            'model' => 'TblShift',
            'ref_key' => 'id',
            'ref_val' => 'shift',
            'data_mapping' => ['A' => 'All', 'M' => 'Morning', 'E' => 'Evening'],
        ],
        'shift_applicability' => [
            'model' => 'TblShift',
            'ref_key' => 'id',
            'ref_val' => 'shift',
            'data_mapping' => ['A' => 'All', 'M' => 'Morning', 'E' => 'Evening'],
        ],
        'milk_quality_type' => [
            'model' => 'TblMilkQualityType',
            'ref_key' => 'milk_quality_type_code',
            'ref_val' => 'milk_quality_type_name',
            'data_mapping' => ['G' => 'GOOD', 'C' => 'CURD', 'S' => 'SOUR', 'D' => 'DRAIN'],
        ],
        'qty_auto' => [
            'is_static' => TRUE,
            'data_mapping' => ['A' => 0, 'M' => 1], // A-Auto,M-Manual
        ],
        'qlty_auto' => [
            'is_static' => TRUE,
            'data_mapping' => ['A' => 0, 'M' => 1], // A-Auto,M-Manual
        ],
        'qty_mode' => [
            'is_static' => TRUE,
            'data_mapping' => ['L' => 0, 'K' => 1], //L-Ltr,K-Kg
        ],
    ];

    function __construct() {
        set_error_handler(array($this, 'handleError'));
    }

    public function handleError($code, $message, $file, $line) {
        return;
    }

    public function manipulation($data) {
        $this->data = $data;
        $this->parseData();
        switch (1) {
            case empty($this->data['svc']):
                return $this->responseData(0, 'nosvc');
            case empty($this->data['usr']):
                return $this->responseData(0, 'usr');
            case empty($this->data['pswd']):
                return $this->responseData(0, 'pswd');
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
        $this->model = new TblDcsConfig();
        $this->model->scenario = 'default';
        return $this->saveData();
    }

    private function saveTmccMemberList() {
        $this->model = new StellappsMember();
        $this->model->scenario = 'default';
        return $this->saveData();
    }

    private function saveTmccPriceChart() {
        $this->model = new TblPurchaseRate();
        return $this->savePriceData();
    }

    private function saveTmccPriceChartMapping() {
        $this->model = new TblPurchaseRateApplicability();
        $this->model->scenario = 'stellapps';
        return $this->saveData();
    }

    private function saveBmcCollection() {
        $this->model = new StellappsCollection();
        $this->model->scenario = 'default';
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
                    }
                }
            }
            $saveModel->scenario = $this->model->scenario;
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
        $saveModel->purchase_rate_code = $saveModel->getCode();
        $saveModel->attributes = $this->data;
        $oldData = $saveModel->getReferenceRecord();
        if (empty($oldData)) {
            foreach ($saveModel->attributes as $key => $a) {
                $type = $schema->columns[$key]->type;
                if ($type == 'date' && $a != '') {
                    $dt = new \DateTime($a);
                    $a = $dt->format('Y-m-d\TH:i:s.u');
                    $saveModel->$key = $a;
                }
            }
            $saveData[] = $saveModel;
        } else {
            $saveModel = $oldData;
        }
        $saveModel->scenario = 'stellapps';
        $schema = $saveModel->getTableSchema();
        $valid[] = $saveModel->validate();
        if ($saveModel->validate() != FALSE) {
            $fat_array = [];
            $snf_array = [];
            $cnt = 0;
            foreach ($this->data['data'] as $value) {
                $rate_detail = new TblPurchaseRateDetails();
                $rate_detail->attributes = $this->data;
                $rate_detail->attributes = $saveModel->attributes;
                $rate_detail->code = $rate_detail->getCode() + $cnt;
                $cnt++;
                $rate_data = explode(',', $value);
                if (count($rate_data) == 3) {
                    $fat_array[] = $rate_detail->fat = $rate_data[0];
                    $snf_array[] = $rate_detail->snf = $rate_data[1];
                    $rate_detail->rtpl = $rate_data[2];
                }
                $schema = $rate_detail->getTableSchema();
                $valid[] = $rate_detail->validate();
                if ($rate_detail->validate() != FALSE) {
                    foreach ($rate_detail->attributes as $key => $a) {
                        $type = $schema->columns[$key]->type;
                        if ($type == 'date' && $a != '') {
                            $dt = new \DateTime($a);
                            $a = $dt->format('Y-m-d\TH:i:s.u');
                            $rate_detail->$key = $a;
                        }
                    }
                }
                $saveData[] = $rate_detail;
            }
            $qlty_param = explode('+', $this->data['rate_type']);
            $qualityModel = new TblQualityParam();
            $qualityParam = $qualityModel->getParams();
            $cnt = 0;
            foreach ($qlty_param as $param) {
                $basedModel = new TblPurchaseRateBased();
                $basedModel->scenario = 'stellapps';
                $basedModel->attributes = $this->data;
                $basedModel->attributes = $saveModel->attributes;
                $basedModel->rate_based_code = $basedModel->getCode() + $cnt;
                $cnt++;
                $basedModel->quality_param_code = array_search($param, $qualityParam);
                if ($param == 'FAT') {
                    $basedModel->start_range = min($fat_array);
                    $basedModel->end_range = max($fat_array);
                } else {
                    $basedModel->start_range = min($snf_array);
                    $basedModel->end_range = max($snf_array);
                }
                $schema = $basedModel->getTableSchema();
                $valid[] = $basedModel->validate();
                if ($basedModel->validate() != FALSE) {
                    foreach ($basedModel->attributes as $key => $a) {
                        $type = $schema->columns[$key]->type;
                        if ($type == 'date' && $a != '') {
                            $dt = new \DateTime($a);
                            $a = $dt->format('Y-m-d\TH:i:s.u');
                            $basedModel->$key = $a;
                        }
                    }
                }
                $saveData[] = $basedModel;
            }
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

    private function parseData() {
        $this->setUnsetAttribute();
        $this->setReferenceAttribute();
        isset($this->data['tmcc_code']) ? $this->data['dcs_code'] = $this->validateTmccCode($this->data['tmcc_code']) : '';
    }

    private function setUnsetAttribute() {
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

    private function setReferenceAttribute() {
        foreach ($this->reference_att as $key => $att) {
            if (isset($this->data[$key])) {
                if (isset($att['is_static']) && $att['is_static'] == TRUE) {
                    $this->data[$key] = $att['data_mapping'][$this->data[$key]];
                } else {
                    $ref_val = isset($att['data_mapping']) ? $att['data_mapping'][$this->data[$key]] : $this->data[$key];
                    $model_name = Yii::$app->path->define($att['model']);
                    $model = new $model_name();
                    $record = $model->find()
                                    ->select($att['ref_key'])
                                    ->where([$att['ref_val'] => $ref_val])->one();
                    !empty($record) ? $this->data[$key . '_code'] = $record->{$att['ref_key']} : '';
                }
            }
        }
        foreach ($this->data['data'] as $index => $data) {
            foreach ($this->reference_att as $key => $att) {
                if (isset($this->data['data'][$index][$key])) {
                    if (isset($att['is_static']) && $att['is_static'] == TRUE) {
                        $this->data['data'][$index][$key] = $att['data_mapping'][$this->data['data'][$index][$key]];
                    } else {
                        $ref_val = isset($att['data_mapping']) ? $att['data_mapping'][$this->data['data'][$index][$key]] : $this->data['data'][$index][$key];
                        $model_name = Yii::$app->path->define($att['model']);
                        $model = new $model_name();
                        $record = $model->find()
                                        ->select($att['ref_key'])
                                        ->where([$att['ref_val'] => $ref_val])->one();
                        !empty($record) ? $this->data['data'][$index][$key . '_code'] = $record->{$att['ref_key']} : '';
                    }
                }
            }
            isset($this->data['data'][$index]['tmcc_code']) ? $this->data['data'][$index]['dcs_code'] = $this->validateTmccCode($this->data['data'][$index]['tmcc_code']) : '';
        }
    }

    public function validateTmccCode($tmcc_code) {
        $dcs_code = NULL;
        if (!empty($tmcc_code)) {
            $code = TblSocietyCodes::find()->where(['bipl_code' => $tmcc_code])->one();
            if (!empty($code)) {
                $dcs_code = $code->dcs_code;
            }
        }
        return $dcs_code;
    }

}
