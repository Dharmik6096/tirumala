<?php

namespace app\modules\vendorapi\components;

use yii\helpers\Json;
use Yii;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;

class HttpRequest extends \yii\base\Component {

    public $request;
    public $allow_call = FALSE;

    public function ParseRequest() {
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

}
