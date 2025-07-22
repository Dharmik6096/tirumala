<?php

use app\components\ActiveForm;
use yii\helpers\Html;
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
            <img src="<?= $this->theme->getUrl('/assets/images/nav_logo.png') ?>" alt="AMCS Logo" class="logo img-responsive" />
        </a>
    </div>
</div>
<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">
                    <?= Yii::t('app', 'Thirumala Application') ?>
                </h4>
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
                    <div id="org" class="col-sm-12">
                        <?= Html::activeHiddenInput($model, 'organization'); ?>
                        <?= Html::hiddenInput('state', $state); ?>
                    </div>
                    <div class="col-sm-12">
                        <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? \Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'rememberMe', true) : '' ?>
                    </div>
                    <div class="col-sm-12">
                        <?=
                        Html::button(
                            Yii::t('app', 'Login'),
                            ['class' => 'btn-login-second btn btn-primary btn-block login-submit']
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
                <div class="panel panel-default panel-grid panel-main bg_image">
                    <div class="panel-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="login-content auto_width_img text-center">
                                        <?php $image_path = Yii::$app->request->baseUrl . '/themes/emilk/assets/images/'; ?>
                                        <?= Html::img($image_path . 'title.png', ['class' => 'img-responsive auto_margin']); ?>
                                        <!--<h3>Welcome to EIPL Milk Procurement Portal</h3>-->
                                        <hr>
                                        <?php $title = Yii::t('app', 'Thirumala Application'); ?>
                                        <div class="col-sm-6 content_show" data-title="<?= $title ?>" id="thirumala">
                                            <?= Html::img($image_path . 'thirumala_logo.png', ['class' => 'img-responsive about_one']); ?>
                                        </div>
                                        <?php $title = Yii::t('app', 'Anik Application'); ?>
                                        <div class="col-sm-6 content_show" data-title="<?= $title ?>" id="anik">
                                            <?= Html::img($image_path . 'anik_logo.png', ['class' => 'img-responsive about_two']); ?>
                                        </div>
                                        <!--<div class="clearfix"></div>-->
                                        <div class="col-sm-12">
                                            <h3 class="mt15">About Us</h3>
                                            <p class="text-justify show_hide content_area thirumala">Thirumala. Everest
                                                brings to the industry, the most precise and advanced technology to
                                                simplify dairy and food testing process. All our products facilitate
                                                better functionality, which ultimately leads to better products reaching
                                                the consumers. This is only possible by making our products and services
                                                technologically innovative and accurate. Our vow to serve the purest
                                                evokes in us, the zeal to deliver the best, through precision, whatever
                                                we do. Our endeavor is to be the personification of the concept of
                                                "Precision Behind Purity"</p>
                                            <p class="text-justify show_hide content_area anik">Anik. Everest brings to
                                                the industry, the most precise and advanced technology to simplify dairy
                                                and food testing process. All our products facilitate better
                                                functionality, which ultimately leads to better products reaching the
                                                consumers. This is only possible by making our products and services
                                                technologically innovative and accurate. Our vow to serve the purest
                                                evokes in us, the zeal to deliver the best, through precision, whatever
                                                we do. Our endeavor is to be the personification of the concept of
                                                "Precision Behind Purity"</p>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-login" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Login</a>
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
</div>
<?= $this->render('paymentCheck') ?>
<?php
if (!empty($model->getErrors()) && !Yii::$app->session->hasFlash('success')) {
    $script = "
        $('#loginModal').modal('show');
        ";
    $this->registerJs($script, View::POS_READY, 'login-code');
}
$script_two = "
        $('.anik').hide()
        $('.content_show').on('click', function(){
            var id = $(this).attr('id');
            var title = $(this).attr('data-title');
            $('.modal-title').text(title);
            $('.content_area').hide();
            $('.'+id).show();
        });
        ";
$this->registerJs($script_two, View::POS_READY, 'change_content_union');
$css = <<<CSS
CSS;
$this->registerCss($css);
?>