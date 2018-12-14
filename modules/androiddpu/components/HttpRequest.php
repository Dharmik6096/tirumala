<?php

namespace app\modules\androiddpu\components;

use yii\helpers\Json;
use Yii;
use app\modules\webservice\models\TblApiRequestLog;
use app\modules\webservice\models\TblAppActivation;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;

class HttpRequest extends \yii\base\Component {

    public $token;
    public $imei;
    public $request_time;
    public $identity_code;
    public $member_code;
    public $type;
    public $device_id;
    public $content = [];
    public $req_url;
    public $is_free = ['android-dpu/register', 'android-dpu/verification', 'android-dpu/initialization'];
    public $request;
    public $allow_call = FALSE;
    public $action_url;

    public function ParseRequest() {

        $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        $request = $this->camelCaseToUnderscore($post_data);
        $request['dcs_code'] = $request['identity_code'];
        if (!empty($request['type']) && in_array($request['type'], [5])) {
            $this->action_url = $request['svc'];
        } else {
            $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            $this->action_url = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        }
        $this->request = $request;
        $this->allow_call = $this->AuthenticateRequest();
        $this->setRequestLog();
        if ($this->allow_call) {
            return $this->request;
        }
        return FALSE;
    }

    public function camelCaseToUnderscore($post_data) {
        if (is_array($post_data)) {
            $post_data = array_combine(array_map(function($str) {
                        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                    }, array_keys($post_data)), array_values($post_data));
            foreach ($post_data as $key => $val) {
                if (is_array($post_data[$key])) {
                    $arr1 = array_combine(array_map(function($str) {
                                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                            }, array_keys($post_data[$key])), array_values($post_data[$key]));
                    $post_data[$key] = $arr1;
                }
            }
            return $post_data;
        }
    }

    private function setRequestLog() {
        $log = new TblApiRequestLog();
        $log->setAttributes($this->request);
        $log->content = Yii::$app->request->getRawBody();
        $log->request_url = $this->req_url;
        $log->request_time = date('Y-m-d H:i:s');
        $log->is_called = $this->allow_call;
        $log->save();
    }

    private function AuthenticateRequest() {
        $message = [];
//        if (!empty($this->request['imei'])) {
        if (in_array($this->action_url, $this->is_free)) {
            return TRUE;
        } else if (!empty($this->request['token'])) {
            $model = new TblAppActivation();
            $model->hash_key = $this->request['token'];
            $model->imei_no = $this->request['imei'];
            $model->type = $this->request['type'];
            if ($model->type == 1) {
                $model->code = $this->request['dcs_code'];
            } else if ($model->type == 2) {
                $model->code = $this->request['member_code'];
            } else if ($model->type == 3) {
                $model->code = $this->request['bmc_code'];
            } else if ($model->type == 4) {
                $model->code = $this->request['username'];
            }
            if ($model->getActiveRecord() == 1) {
                return TRUE;
            } else {
                $message[] = 'Authentication Failed.';
            }
        } else {
            $message[] = 'Authentication Failed.';
        }
//        } else {
//            $message[] = 'Imei Can not be blank.';
//        }
        Yii::$app->apiError->error($message, 'error');
        return FALSE;
    }

}
