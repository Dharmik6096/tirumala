<?php

namespace app\modules\vendorapi\components;

use yii\helpers\Json;
use Yii;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;
use app\modules\vendorapi\models\TblVendorApiRequestLog;

class HttpRequest extends \yii\base\Component {

    public $request;
    public $allow_call = FALSE;
    public $log_id;

    public function ParseRequest() {
        $this->saveVendorApiLog();
        $request = Json::decode(Yii::$app->request->getRawBody());
        $this->request = $request;
        $this->allow_call = $this->AuthenticateRequest();
        if ($this->allow_call) {
            return $this->request;
        }
        return FALSE;
    }

    private function AuthenticateRequest() {
        $message = '';
        $model = new User();
        $model->setAttributes($this->request);
        $model->password_hash = $model->password;
        $identityModel = new \app\models\IdentityMaster();
        $identity = $identityModel->getIdentity();
        $model->username = $identity->organization_code . '#' . $model->username;
        $user_data = $model->find()->where(['username' => $model->username])->one();
        if (!empty($user_data) && !empty($model->password_hash)) {
            $validate = Yii::$app->security->validatePassword($model->password_hash, $user_data->password_hash);
            if ($validate) {
                $user_org_map = new TblUserOrganizationMapping;
                $user_org_map->organization_type = 'UNION';
                $user_org_map->user_id = $user_data->id;
                $user_org = $user_org_map->getUserOrgMapping();
                if (!empty($user_org) && count($user_org) == 1) {
                    $this->request['union_code'] = $user_org[0]->organization_code;
                    $this->request['log_id'] = $this->log_id;
                    return TRUE;
                } else {
                    $message = 'Invalid Credentials.';
                }
            } else {
                $message = 'Invalid Credentials.';
            }
        } else {
            $message = 'Invalid Credentials.';
        }
        Yii::$app->vendorApiError->error($message, 'error');
        return FALSE;
    }

    private function saveVendorApiLog() {
        $array = [];
        $array['server'] = $_SERVER;
        $array['header'] = getallheaders();
        $array = json_encode($array);
        $log_model = new TblVendorApiRequestLog();
        $log_model->url = Yii::$app->request->absoluteUrl;
        $log_model->request = Yii::$app->request->getRawBody();
        $log_model->request_original = $array;
        $log_model->request_ip = $_SERVER['REMOTE_ADDR'];
        $log_model->status = true;
        $log_model->created_at = date('Y-m-d H:i:s');
        $log_model->save();
        $this->log_id = $log_model->log_id;
    }

}
