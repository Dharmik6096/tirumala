<?php

namespace app\modules\vendorapi\models;

use Yii;
use ReflectionClass;
use app\models\GeneralModel;
use DateTime;
use app\modules\vendorapi\models\TblVendorApiData;
use webvimark\modules\UserManagement\models\User;

class VendorModel {

    private function setParam($svc) {
        $data = [
            'route_master' => [
                'route_code' => 'master_code',
                'route_name' => 'master_name',
                'route_type' => 'master_type',
                'route_destination' => 'parent_code',
                'vehicle_type' => 'type_2',
                'vehicle_capacity' => 'capacity',
                'route_length' => 'route_length',
                'route_start_date' => 'date_1:date',
                'morning_start_time' => 'time_1',
                'morning_end_time' => 'time_2',
                'evening_start_time' => 'time_3',
                'evening_end_time' => 'time_4',
                'contact_person_fname' => 'contact_first_name',
                'contact_person_mname' => 'contact_middle_name',
                'contact_person_lname' => 'contact_last_name',
                'email' => 'email',
                'mobile_no' => 'mobile_no',
                'is_active' => 'is_active',
                'scenario' => 'scenario'
            ]
        ];
        return isset($data[$svc]) ? $data[$svc] : [];
    }

    public function manipulation($data) {
        $model = new User();
        $model->setAttributes($data);
        $model->password_hash = $model->password;
        $identityModel = new \app\models\IdentityMaster();
        $identity = $identityModel->getIdentity();
        $model->username = $identity->organization_code . '#' . $model->username;
        $user_data = $model->find()->where(['username' => $model->username])->one();
        $status = '';
        $message = '';
        if (!empty($user_data) && !empty($model->password_hash)) {
            $validate = Yii::$app->security->validatePassword($model->password_hash, $user_data->password_hash);
            if ($validate) {
                $generalModel = new GeneralModel();
                $svc = $data['svc'];
                $save_data = $data['data'];
                $master_model = [];
                $valid = [];
                foreach ($save_data as $model_data) {
                    $model = new TblVendorApiData();
                    $params = $this->setParam($svc);
                    if (isset($params['scenario'])) {
                        $model->scenario = $svc;
                    }
                    foreach ($params as $key => $value) {
                        $param = explode(':', $value);
                        if ($key != $param[0]) {
                            $model_data[$param[0]] = isset($model_data[$key]) ? $model_data[$key] : NULL;
                        }
                        if (isset($param[1]) && $param[1] == 'date') {
                            $model_data[$param[0]] = !empty($model_data[$param[0]]) ? date('Y-m-d', strtotime($model_data[$param[0]])) : '';
                        }
                    }
                    $model->setAttributes($model_data);
                    $model->username = $data['username'];
                    $model->password = $data['password'];
                    $model->service_type = $data['svc'];
                    $model->type_of_data = 'JSON';
                    $valid[] = $model->validate();
                    $master_model[] = $model;
                }
                $path = Yii::$app->params['vendorApiErrorLogPath'];
                if (in_array(FALSE, $valid)) {
                    $dir = $this->checkDirectory($path);
                    if ($dir) {
                        $logs = [];
                        foreach ($master_model as $smodel) {
                            $logs['data'][] = $smodel->getAttributes();
                            $logs['errors'][] = $smodel->getErrors();
                        }
                        $text = json_encode($logs);
                        $this->createCpLogFile($path, $text, $svc);
                    }
                } else {
                    $transaction = $generalModel->saveTransaction($master_model, ['vendor services', 'create']);
                    if ($transaction == 'customRedirect') {
                        $status = 'Success';
                        $message = Yii::t('app', 'Successfully Saved!');
                    } else {
                        $status = 'Error';
                        $message = Yii::t('app', 'Unable to save!');
                        $dir = $this->checkDirectory($this->path);
                        if ($dir) {
                            $text = 'Error ocuured while saving data!!';
                            $error_text = $text;
                            $this->createCpLogFile($path, $text, $svc);
                        }
                    }
                }
            } else {
                $status = 'Error';
                $message = Yii::t('app', 'Username or password is invalid.');
            }
        }
        $response = ["status" => $status, "msg" => $message];
        return $response;
    }

    protected function createCpLogFile($path, $text, $cp_code) {
        if (!empty($cp_code)) {
            $path = $path . '\\' . $cp_code;
        }
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

}
