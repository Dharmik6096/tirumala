<?php

namespace app\modules\webservice\components;

use Yii;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblEiplApiRequestLog;

class EiplRequest {

    private $req_url;

    public function ParseRequest() {
        if (strpos(Yii::$app->request->headers['content-type'], 'multipart/form-data') === 0 && !empty($_REQUEST['formData'])) {
            Yii::$app->request->setRawBody($_REQUEST['formData']);
        }
        //   $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $this->setRequestLog();
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        $request = $this->camelCaseToUnderscore($post_data);
        /* $header = getallheaders();
          $array['header'] = $header;
          $request['endpoint'] = !empty($header['endpoint']) ? $header['endpoint'] : NULL; */
        \Yii::$app->request->setRawBody($request);
        return TRUE;
    }

    private function camelCaseToUnderscore($post_data) {
        if (is_array($post_data)) {
            $post_data = array_combine(array_map(function($str) {
                        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                    }, array_keys($post_data)), array_values($post_data));
            foreach ($post_data as $key => $val) {
                if (is_array($val)) {
                    $post_data[$key] = $this->camelCaseToUnderscore($val);
                    // $arr1 = array_combine(array_map(function($str) {
                    //             return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                    //         }, array_keys($post_data[$key])), array_values($post_data[$key]));
                    // $post_data[$key] = $arr1;
                }
            }
            return $post_data;
        }
    }

    private function setRequestLog() {
        $header = getallheaders();
        $log = new TblEiplApiRequestLog();
        $log->access_token = !empty($header['Authorization']) ? $header['Authorization'] : NULL;
        $log->request_url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $log->request_body = Yii::$app->request->getRawBody();
        $log->request_time = date('Y-m-d H:i:s');
        $log->save();
    }

}
