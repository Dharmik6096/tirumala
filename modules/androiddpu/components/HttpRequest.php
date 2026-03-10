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
    public $is_free = ['android-dpu/register', 'android-dpu/verification', 'realtime-services/dpu-product-stock', 'esp-app/register', 'esp-app/verification', 'android-dpu/send-otp', 'android-dpu/change-password','app-activation/register', 'app-activation/verification', 'android-dpu/fatscan-offset', 'android-dpu/fatscan-data'];
    public $request;
    public $allow_call = FALSE;
    public $action_url;
    public $sync_key;

    public function ParseRequest() {
        $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $request = Yii::$app->request->getBodyParams();

        if (Yii::$app->request->isGet) {
            $request = Yii::$app->request->get();
        }
        if (empty($request)) {
            $request = !empty(Yii::$app->request->post()['requestData']) ? Json::decode(Yii::$app->request->post()['requestData']) : [];
        }
       // $request = $this->camelCaseToUnderscore($post_data);
//        $request['dcs_code'] = $request['identity_code'];
        if (!empty($request['type']) && in_array($request['type'], [5])) {
            $this->action_url = $request['svc'];
        } else {
            $this->req_url = Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            $this->action_url = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        }
        $this->request = $request;
        $this->allow_call = $this->AuthenticateRequest();
//        $this->setRequestLog();
        if ($this->allow_call) {
            return $this->request;
        }
        return FALSE;
    }


    private function setRequestLog() {
        $log = new TblApiRequestLog();
        $log->setAttributes($this->request);
        $log->content = Yii::$app->request->getRawBody();
        $log->request_url = $this->req_url;
        $log->request_time = date('Y-m-d H:i:s');
        $log->is_called = (int) $this->allow_call;
        $log->save();
    }

    private function AuthenticateRequest() {
        $message = [];
//        if (!empty($this->request['imei'])) {
        if (in_array($this->action_url, $this->is_free)) {
            return TRUE;
        } else if (!empty($this->request['token'])) {
            // try redis cache first
            $cacheKey = 'androiddpu:auth:' . md5($this->request['token'] . '|' . (!empty($this->request['device_id']) ? $this->request['device_id'] : '') . '|' . (!empty($this->request['organization_code']) ? $this->request['organization_code'] : '') . '|' . (!empty($this->request['organization_type']) ? $this->request['organization_type'] : ''));
            try {
                if (!empty(Yii::$app) && Yii::$app->has('redis')) {
                    $redis = Yii::$app->get('redis');
                    $cached = $redis->get($cacheKey);
                    if ($cached !== null && $cached !== false) {
                        if ((string)$cached === '1') {
                            return TRUE;
                        } else {
                            $message[] = 'Authentication Failed.';
                            Yii::$app->apiError->error($message, 'error');
                            return FALSE;
                        }
                    }
                }
            } catch (\Exception $e) {
                // ignore redis failures and fallback to DB
            }

            $model = new TblAndroidInstallationDetails();
            if ($model->getActiveRecordCount($this->request) == 1) {
                // cache positive auth for 4.17hr (15000 seconds)
                try {
                    if (!empty(Yii::$app) && Yii::$app->has('redis')) {
                        $redis = Yii::$app->get('redis');
                        $redis->setex($cacheKey, 15000, '1');
                    }
                } catch (\Exception $e) {
                    // ignore redis failures
                }
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
