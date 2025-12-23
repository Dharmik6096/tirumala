<?php

namespace app\modules\vendorapi\controllers;

use yii\web\Controller;
use app\modules\vendorapi\controllers\RestController;
use Yii;
use ReflectionClass;
use DateTime;
use app\modules\vendorapi\models\TblVendorApiData;
use app\modules\usermanagement\models\User;
use app\modules\vendorapi\Vendorapi;
use app\models\TblUserOrganizationMapping;

/**
 * Default controller for the `vendorapi` module
 */
class VendorController extends RestController {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionVendorServices() {
        return $this->manipulation($this->post_data);
    }

    public function manipulation($data) {
        $success_codes = [];
        $error_codes = [];
        $svc = $data['svc'];
        $master_array = Vendorapi::setParam($svc);
        if (!empty($master_array) && isset($data[$master_array['json_key']])) {
            $data_key = $master_array['content_json_key'];
            $save_data = $data[$master_array['json_key']][$data_key];
            $array = [];
            $array[] = $save_data;
            $convert_array = isset($save_data[0]) ? $save_data : $array;
            $save_data = $convert_array;
            $master_model = [];
            $valid = [];
            foreach ($save_data as $model_data) {
                $model = new TblVendorApiData();
                $params = Vendorapi::setParam($svc);
                if (isset($params['scenario'])) {
                    $model->scenario = $svc;
                }
                foreach ($params as $key => $value) {
                    $param = explode(':', $value);
                    if ($key != $param[0]) {
                        if (!empty($model_data[$key])) {
                            $model_data[$key] = substr($model_data[$key], 0, 1) == '"' && substr($model_data[$key], -1, 1) == '"' ? substr($model_data[$key], 1, -1) : $model_data[$key];
                        }
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
                $model->union_code = $data['union_code'];
                $model->log_id = $data['log_id'];
                $model->mobile_no = (string) $model->mobile_no;
                $model->parent_code = (string) $model->parent_code;
                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = $data['union_code'];
                $model->master_code = !empty($model->master_code) ? (string) $model->master_code : NULL;
                $model->bank_account_no = !empty($model->bank_account_no) ? (string) $model->bank_account_no : NULL;
                $res = [];
                if ($model->validate() && $model->save()) {
                    $success_codes[] = $model->master_code;
                } else {
                    $error_codes[] = $model->master_code;
                    $valid[] = $model->validate();
                }
                $response[] = $res;
                $master_model[] = $model;
            }
            $path = Yii::getAlias('@webroot') . "/" . Yii::$app->params['vendorApiErrorLogPath'];
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
            }
        }
        $this->response['success_codes'] = $success_codes;
        $this->response['error_codes'] = $error_codes;
        return $this->response;
    }

    protected function createCpLogFile($path, $text, $cp_code) {
        $dir = $this->checkDirectory($path);
        if ($dir) {
            $fileName = $path . "/" . date('YmdHis') . '_' . $cp_code . '.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

    protected function checkDirectory($path) {
        if (file_exists($path)) {
            if (!is_dir($path)) { //if file is already present, but it's not a dir
                if (mkdir($path, 0777, true) == false) {
                    die('Failed to create folders...' . $path);
                    return false;
                }
            }
        } else { //no file exists with this name
            if (mkdir($path, 0777, true) == false) {
                die('Failed to create folders...' . $path);
                return false;
            }
        }
        return true;
    }

}
