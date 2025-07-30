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
            <img src="<?= $this->theme->getUrl('/assets/images/logo.png') ?>" alt="AMCS Logo" class="logo img-responsive" />
        </a>
    </div>
</div>

<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><?= Yii::t('app', 'EVEREST Application') ?></h4>
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
                <div class="row">
                    <div class="col-sm-12">
                        <?=
                        $form->field($model, 'username')
                            ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off'])
                        ?>
                    </div>
                    <div class="col-sm-12">
                        <?=
                        $form->field($model, 'password')
                            ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'autocomplete' => 'off'])
                        ?>
                    </div>
                    <?php //if($identity['organization_type']!='NATIONAL'){  
                    ?>

                    <div id="org" class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php //$form->field($model, 'type', ['options' => ['class' => 'form-group']])->dropDownList(['PCDF' => 'PCDF', 'UNION' => 'UNION'], ['prompt' => 'Select Organization']); 
                                ?>
                            </div>
                        </div>
                        <?= Html::activeHiddenInput($model, 'organization'); ?>
                        <?= Html::hiddenInput('state', $state); ?>
                    </div>
                    <?php //}  
                    ?>
                    <div class="col-sm-12">
                        <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? \Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'rememberMe', true) : '' ?>
                    </div>
                    <div class="col-sm-12">
                        <?=
                        Html::button(
                            Yii::t('app', 'Login'),
                            ['class' => 'btn-login btn btn-primary btn-block login-submit']
                        )
                        ?>
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
                                    <div class="login-content text-center">
                                        <h3>Welcome to EIPL Milk Procurement Portal</h3>
                                        <hr>
                                        <?php $image_path = Yii::$app->request->baseUrl . '/themes/emilk/assets/images/'; ?>
                                        <div class="col-sm-12">
                                            <?= Html::img($image_path . 'vrs.png', ['class' => 'img-responsive']); ?>
                                        </div>
                                        <h3>About Us</h3>
                                        <p class="text-justify">Everest brings to the industry, the most precise and advanced technology to simplify dairy and food testing process. All our products facilitate better functionality, which ultimately leads to better products reaching the consumers. This is only possible by making our products and services technologically innovative and accurate. Our vow to serve the purest evokes in us, the zeal to deliver the best, through precision, whatever we do. Our endeavor is to be the personification of the concept of "Precision Behind Purity"</p>
                                        <!--                                  <p class="text-justify">Everest, keeping the above in mind, has entered into MOU with Gujarat Knowledge Application &Facilitation Centre of Confederation of Indian Industries, Western Branch and Anand Agriculture University to conduct research for our instruments to evaluate the performance, suggest further modification and upon their approval, issue the necessary accreditations.</p>
                                        <p class="text-justify">Everest is being modernized inconformity with a plan for a number of purposes, including raising the technological level of production. In addition, thereby increasing the volume of output by eliminating bottlenecks, systematizing production and improving management. It is also envisaged significant capital investments to have production building sand installations, auxiliary facilities, administrative buildings, etc. The Indian Government is extensively promoting milk production through intensive dairy development programs and strengthening of infrastructure for quality and clean milk production. Everest is poised to take maximum advantage of it by extending its infrastructural facilities in a big way.</p>
                                        <p class="text-justify">IT is our Best Assets. Everest offers impeccable IT solution/integration to its clientele. Our full-fledged IT Team do research to customize the requirement every customers be it a small entrepreneur or a dairy giant like NDDB. Everest’s IT Team has been selected for conceptualizing and implementing a new software for milk collection across our country.</p>
                                        <p class="text-justify">Everest now employs a workforce of 350 techno commercial personnel and its Engineering field staff are made available for 24x7 service. Everest Care Its Customers and render exemplary services at all times -secret of Everest’s success. Everest has generated revenue over 1000 million INR and it has very ambitious but realistic plan to achieve the coveted 5000 million INR by 2015.</p>-->
                                        <a href="javascript:void(0)" class="btn-login  btn btn-danger" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Login</a>
                                        <div class="ml500 col-xs-12">
                                            <?php echo Html::a('Forgot Password ?', ['/usermanagement/auth/forget-password'], ['title' => 'Click to reset your password']); ?>
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