<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\forms\LoginForm;
use app\modules\organisation\models\TblUnions;

class AuthController extends \webvimark\modules\UserManagement\controllers\AuthController {

    public function actionLogin() {
        $this->layout = '@app/web/themes/emilk/layouts/loginLayout.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $identityModel = new \app\models\IdentityMaster();
        $identity = $identityModel->getIdentity();
        $unionData = TblUnions::find()->where(['is_active' => 1])->one();
        $eiplCode = !empty($unionData) && !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';
        $loginFile = 'login';
        $fileName = 'login_' . strtolower($eiplCode);
        $path = Yii::$app->basePath . '/modules/usermanagement/views/auth/' . $fileName . '.php';
        if (file_exists($path)) {
            $loginFile = $fileName;
        }
        if (empty($identity)) {
            return $this->render('error');
        }
//                if($identity->organization_type!='NATIONAL')
//                    $model->scenario = 'non_national';
        if (Yii::$app->request->isAjax) {
            $model->username = $identity->organization_code . '#' . $model->username;
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->username = $identity->organization_code . '#' . $model->username;
            if ($model->login())
                return $this->redirect(['/site/dashboard']);
            else
                $model->username = $_POST['LoginForm']['username'];
        }
        Yii::$app->session->set('Login-sess', 'User');
        return $this->renderIsAjax($loginFile, compact('model'));
    }

}
