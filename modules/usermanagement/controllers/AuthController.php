<?php

namespace app\modules\usermanagement\controllers;

use Yii;
use app\modules\usermanagement\models\forms\LoginForm;
use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\models\User;
use app\modules\usermanagement\models\forms\ChangeOwnPasswordForm;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\components\ActiveForm;
use app\modules\sms\models\TblAlertNotification;
use app\models\IdentityMaster;
use app\modules\sms\models\TblApiMaster;
use app\models\UserHistory;
use app\modules\sms\models\TblAlertTemplate;
use app\models\GeneralModel;

class AuthController extends \webvimark\modules\UserManagement\controllers\AuthController
{

    public $freeAccessActions = ['forget-password', 'change-password'];

    public function actionLogin()
    {
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
            $username = $model->username = \Yii::$app->EIPLSecurity->UrlDecrypt($model->username, TRUE);
            $model->password = \Yii::$app->EIPLSecurity->UrlDecrypt($model->password, TRUE);
            $model->username = htmlspecialchars(trim($username), ENT_QUOTES);
            $model->username = $identity->organization_code . '#' . $model->username;
            Yii::$app->session->set('login_enc_key', NULL);
            if ($model->validatePassword(true, $userCode, $maxLoginAttempts)) {
                return $this->redirect(['change-password', 'userCode' => $userCode]);
            } else if (empty($maxLoginAttempts) && $model->login()) {
                return $this->redirect(['/site/dashboard']);
            } else {
                if (!empty($maxLoginAttempts)) {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Your account is temporarily suspended. Please try again after ' . $maxLoginAttempts . ' minute(s).']);
                }
                $model->username = $username;
            }
        }
        $login_enc_key = substr(bin2hex(random_bytes(10)), -16);
        Yii::$app->session->set('login_enc_key', $login_enc_key);
        Yii::$app->session->set('Login-sess', 'User');
        return $this->renderIsAjax($loginFile, compact('model'));
    }

    public function actionChangeOwnPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::getCurrentUser();

        if ($user->status != User::STATUS_ACTIVE) {
            throw new ForbiddenHttpException();
        }

        $model = new ChangeOwnPasswordForm(['user' => $user]);


        if (Yii::$app->request->isAjax and $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) and $model->changePassword()) {
            return $this->renderIsAjax('changeOwnPasswordSuccess');
        }

        return $this->renderIsAjax('changeOwnPassword', compact('model'));
    }
    public function actionForgetPassword()
    {
        $this->layout = "@app/web/themes/emilk/layouts/guestLayout.php";
        $model = new User();
        $model->scenario = 'forgetPsd';
        $sentOtp = false;
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $identity = new IdentityMaster();
            $data = $identity->getIdentity();
            $checkUser = $data['organization_code'] . '#' . $model->username;
            $modelData = $model->findOne(['username' => $checkUser, 'portal_type' => strtolower('portal')]);

            if (!empty($modelData)) {
                if (!empty($modelData->email)) {
                    $model = $modelData;
                    $model->load(Yii::$app->request->post());
                    if (Yii::$app->session->get('otp_code')) {
                        $model->scenario = 'verifyOtp';
                    } else {
                        $otp = rand(1000, 9999);
                        Yii::$app->session->set('otp_code', $otp);
                        $apiMaster = new TblApiMaster();
                        $apiMaster->receiver_type = 'EMAIL';
                        $apiMasterData = $apiMaster->getAPI();
                        if (!empty($apiMasterData)) {
                            $templateModel = new TblAlertTemplate();
                            $templateData = $templateModel->getTemplateData('portal_password_reset', 'EMAIL', $apiMaster->union_code);
                            if (!empty($templateData)) {
                                $notificationModel = new TblAlertNotification();
                                $notificationModel->receiver_type = 'EMAIL';
                                $notificationModel->message = str_replace('{OTP}', $otp, $templateData->message);
                                $notificationModel->header_info = $templateData->header_info;
                                $notificationModel->send_status = 0;
                                $notificationModel->content_id = $apiMasterData->api_master_id;
                                $notificationModel->module_type = 'portal_password_reset';
                                $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                                $notificationModel->send_mail = 1;
                                $notificationModel->receiver_detail = $modelData->email;
                                $notificationModel->save();
                            }
                        }
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'message' => 'OTP Sent Successfully'
                        ]);
                    }

                    if ($model->validate() && !empty($model->otp_code)) {
                        $otpCode = Yii::$app->session->get('otp_code');
                        if ($model->otp_code == $otpCode) {
                            $model->username = $checkUser;
                            $historyModel = new UserHistory();
                            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                            $generalModel = new GeneralModel();
                            $transaction = $generalModel->saveTransaction([$model], [$historyModel], ['Password', 'edit']);
                            if ($transaction == 'customRedirect') {
                                return $this->redirect(['/user-management/auth/login']);
                            }
                        } else {
                            $model->addError('otp_code', Yii::t('app', 'Please enter valid OTP.'));
                        }
                    }
                    $sentOtp = true;
                    $model->scenario = 'verifyOtp';
                } else {
                    $model->addError('username', Yii::t('app', 'Email Is Not Available For user ' . $model->username));
                }
            } else {
                $model->addError('username', Yii::t('app', 'Please enter valid Information.'));
            }
        } else {
            Yii::$app->session->remove('otp_code');
        }
        return $this->renderIsAjax('forget_password', ['model' => $model, 'sentOtp' => $sentOtp]);
    }

    public function actionChangePassword($userCode = '') {
        $this->layout = "@app/web/themes/emilk/layouts/guestLayout.php";
        if (empty($userCode)) {
            return $this->goHome();
        }
    
        $user = User::findOne(['id' => $userCode, 'is_active' => 1]);

        $model = new ChangeOwnPasswordForm(['user' => $user]);
    
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->changePassword()) {
                return $this->render('changePassword', ['model' => $model, 'passwordChanged' => true]);
            }
            if (!$model->hasErrors()) {
                Yii::$app->session->setFlash('success', ['type' => 'error','message' => 'Failed to change password. Please check your inputs.']);
            }
        } else {
            Yii::$app->session->setFlash('success', ['type' => 'error', 'message' => 'Your Password is Expired. Kindly update your password.']);
        }
    
        return $this->render('changePassword', ['model' => $model, 'passwordChanged' => false]);
    }

}
