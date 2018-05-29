<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\models\TblAppActivation;
use app\modules\installation\models\InstallationIdentity;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\forms\LoginForm;
use Yii;

class AppActivationController extends ChildController {

    public function actionRegister() {
        $model = new User();
        $model->setAttributes($this->post_data);
        $model->password_hash = $this->post_data['content']['password_hash'];
        $identityModel = new \app\models\IdentityMaster();
        $identity = $identityModel->getIdentity();
        $username = $model->username;
        $model->username = $identity->organization_code . '#' . $username;
        $user_data = $model->find()->where(['username' => $model->username])->one();
        $data = [];
        if (!empty($user_data)) {
            $validate = Yii::$app->security->validatePassword($model->password_hash, $user_data->password_hash);
            if ($validate) {
                $appModel = new TblAppActivation();
                $appModel->setAttributes($this->post_data);
                $appModel->code = $username;
                $appModel->imei_no = $this->post_data['imei'];
                $appModel->hash_key = Yii::$app->security->generateRandomString(20);
                $appModel->orignating_timestamp = date('Y-m-d H:i:s');
                $appModel->sms_sent = 1;
                $appModel->otp_code = '0000';
                $appModel->is_delete = 0;
                $appModel->is_active = 1;
                $appModel->is_expired = 0;
                $transaction = $this->generalModel->saveTransaction([$appModel], ['app registration', 'create']);
                if ($transaction !== 'customRedirect') {
                    return FALSE;
                }
                $data['token'] = $appModel->hash_key;
            } else {
                $message[] = 'Username or password is invalid .';
                Yii::$app->apiError->error($message, 'error');
                return FALSE;
            }
        } else {
            $message[] = 'Username or password is invalid .';
            Yii::$app->apiError->error($message, 'error');
            return FALSE;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

}
