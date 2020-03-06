<?php

namespace app\modules\webservice\components;

use yii\helpers\Json;
use Yii;
use app\modules\webservice\models\TblApiRequestLog;
use app\modules\webservice\models\TblAppActivation;
use app\modules\organisation\models\TblUnions;

class EmilkProLiteRequest extends \yii\base\Component {

    public $token;
    public $imei;
    public $request_time;
    public $identity_code;
    public $member_code;
    public $type;
    public $device_id;
    public $content = [];
    public $req_url;
    public $is_free = [];
    public $request;
    public $allow_call = FALSE;
    public $action_url;

    public function ParseRequest() {
         //   $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        $request = $this->camelCaseToUnderscore($post_data);
        if (!empty($request['type']) && in_array($request['type'], [5])) {
            $this->action_url = $request['svc'];
        } else {
            $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            $this->action_url = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        }
        $this->request = $request;
        $this->allow_call = $this->AuthenticateRequest();

        $this->setRequestLog();
//        \Yii::$app->request->setRawBody($request);
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
        $log->request_url = Yii::$app->controller->module->module->id . '/' . $this->req_url;
        $log->request_time = date('Y-m-d H:i:s');
        $log->is_called = (int) $this->allow_call;
        $log->save();
    }

    private function AuthenticateRequest() {
        $message = [];
        if (in_array($this->action_url, $this->is_free)) {
            return TRUE;
        } else if (!empty($this->request['eipl_code']) && !empty($this->request['eipl_token'])) {
            $model = new TblUnions();
            $model->eipl_code = $this->request['eipl_code'];
            $model->eipl_token = $this->request['eipl_token'];
            if ($model->emilkProLiteUnionCount() == 1) {
                return TRUE;
            } else {
                $message[] = 'Authentication Failed.';
            }
        } else {
            $message[] = 'Authentication Failed.';
        }
        Yii::$app->apiError->error($message, 'error');
        return FALSE;
    }

}
