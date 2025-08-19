<?php

/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */
use app\components\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

$state = $model->getStateCode();
$org = ($model->type == 'UNION') ? 'block' : 'none';
if (Yii::$app->session->hasFlash('success')) {
    $msg = Yii::$app->session->getFlash('success');
    if (isset($msg['type'])) {
        Yii::$app->display->show($msg['message'], 'successbar', 'success');
    }
}
?>

<div class="navbar fixed-top menu-wrap">
    <div class="container-fluid">
        <a class="navbar-brand" href="javascript:void(0)">
            <img
                src="<?= $this->theme->getUrl('/assets/images/saahaj_logo.png') ?>" alt="AMCS Logo"
                class="logo img-responsive" /> 
        </a>
    </div>
</div>

<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><?= Yii::t('app', 'SAAHAJ Application') ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['autocomplete' => 'off'],
                            'validateOnBlur' => false,
                            'fieldConfig' => [
                                'template' => "{label}\n{input}\n{error}",
                            ],
                        ])
                ?>

                <?php echo $form->errorSummary($model); ?>
                <?= Html::activeHiddenInput($model, 'username'); ?>
                <?= Html::activeHiddenInput($model, 'password'); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?=
                                $form->field($model, 'login_username')
                                ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off'])
                        ?>
                    </div>
                    <div class="col-sm-12">
                        <?=
                                $form->field($model, 'login_password')
                                ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'autocomplete' => 'off'])
                        ?>
                    </div>
                    <?php //if($identity['organization_type']!='NATIONAL'){  ?>

                    <div id="org" class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php //$form->field($model, 'type', ['options' => ['class' => 'form-group']])->dropDownList(['PCDF' => 'PCDF', 'UNION' => 'UNION'], ['prompt' => 'Select Organization']); ?>
                            </div>
                        </div>
                        <?= Html::activeHiddenInput($model, 'organization'); ?>
                        <?= Html::hiddenInput('state', $state); ?>
                    </div>
                    <?php //}  ?>
                    <div class="col-sm-12">
                        <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? \Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'rememberMe', true) : '' ?>
                    </div>
                    <div class="col-sm-12">
                        <?= Html::button(Yii::t('app', 'Login'), ['class' => 'btn-login btn btn-primary btn-block login-submit', 'type' => 'submit']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-grid panel-main">
                    <div class="panel-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="login-content text-center saahaj_img">
                                        <h3>Welcome to Saahaj  Milk Producer Organization  Portal</h3>
                                        <hr>
                                        <?php $image_path = Yii::$app->request->baseUrl . '/themes/emilk/assets/images/'; ?>
                                        <div class="col-sm-12">
                                            <?= Html::img($image_path . 'saahaj_login_1.jpg', ['class' => 'img-responsive']); ?>
                                        </div>
                                        <h3>About Us</h3>
                                        <p class="text-justify">Saahaj Milk Producer Organization was incorporated on 17th October 2014 with its headquarters located at Agra, in Uttar Pradesh. The MPC commenced its business operations from 12th December 2014.The main objective of Saahaj Milk Producer Organization is to carry on the business of purchasing and processing of milk of its members. Currently Saahaj Milk Producer Organization is operating in 10 districts of Uttar Pradesh & Procured 5.80 Lakh Kg of milk per day from registered members.</p>
                                        <a href="javascript:void(0)" class="btn-login  btn btn-danger" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('paymentCheck') ?>
<?php
if (!empty($model->getErrors()) && !Yii::$app->session->hasFlash('success')) {
    $script = "
        $('#loginModal').modal('show');";
    $this->registerJs($script, View::POS_READY, 'login-code');
}

$css = <<<CSS
        .loginError i {
            font-size: 25px;
            height: 30px;
            width: 30px;
            border: 2px solid #fff;
            color: #fff;
            /* line-height: 46px; */
            border-radius: 50%
        }
CSS;

$this->registerCss($css);
?>