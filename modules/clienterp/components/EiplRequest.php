<?php

namespace app\modules\clienterp\components;

use Yii;
use yii\helpers\Json;
use app\modules\webservice\models\TblApiRequestLog;

class EiplRequest {

    public $req_url;
    public $allow_call = FALSE;

    public function ParseRequest() {
        $this->allow_call = $this->AuthenticateRequest();
        $this->setRequestLog();
        if ($this->allow_call) {
            $post_data = Json::decode(Yii::$app->request->getRawBody());
            $request = $this->camelCaseToUnderscore($post_data);
            \Yii::$app->request->setRawBody($request);
            return TRUE;
        }
        return FALSE;
    }

    private function AuthenticateRequest() {
        $header = getallheaders();
        $auth = Yii::$app->params['clienterp_authentication'][Yii::$app->controller->module->id];
        $is_authenticated = FALSE;
        if ($auth['auth_type'] == 'header') {
            $detail = $auth['auth_detail'];
            foreach ($detail as $param => $value) {
                if (!empty($header[$param]) && ($header[$param] == $value)) {
                    $is_authenticated = TRUE;
                } else {
                    $is_authenticated = FALSE;
                    break;
                }
            }
        }
        if (!$is_authenticated) {
            $setError = new SetError();
            $setError->error('Authentication Failed.', 'statusAuthorizationFail');
            return FALSE;
        }
        return TRUE;
    }

    private function camelCaseToUnderscore($post_data) {
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
        $request = [];
        $header = getallheaders();
        $log = new TblApiRequestLog();
        $log->request_url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $request['content'] = Yii::$app->request->getRawBody();
        $request['requestHeader'] = $header;
        $log->content = json_encode($request);
        $log->request_time = date('Y-m-d H:i:s');
        $log->is_called = (int) $this->allow_call;
        $log->save();
    }

}
