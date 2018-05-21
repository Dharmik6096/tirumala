<?php

/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

$state = $model->getStateCode();
$org = ($model->type == 'UNION') ? 'block' : 'none';
?>

<div class="navbar navbar-fixed-top menu-wrap">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:void(0)"><img src="<?= $this->theme->getUrl('/assets/images/logo.png') ?>" alt="AMCS Logo" class="logo img-responsive"/></a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in"></i> <span>Login</span></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= UserManagementModule::t('front', 'PCDF Application') ?></h4>
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
                    <?php //if($identity['organization_type']!='NATIONAL'){  ?>

                    <div id="org" class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'type', ['options' => ['class' => 'form-group']])->dropDownList(['PCDF' => 'PCDF', 'UNION' => 'UNION'], ['prompt' => 'Select Organization']); ?>
                            </div>
                        </div>
                        <?= Html::activeHiddenInput($model, 'organization'); ?>
                        <?= Html::hiddenInput('state', $state); ?>
                    </div>
                    <?php //}  ?>
                    <div class="col-sm-12">
                        <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? $form->field($model, 'rememberMe', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['value' => true]) : '' ?>
                    </div>
                    <div class="col-sm-12">
                        <?=
                        Html::submitButton(
                                UserManagementModule::t('front', 'Login'), ['class' => 'btn btn-primary btn-block']
                        )
                        ?>
                    </div>
                </div>
                <div class="row registration-block">
                    <div class="col-sm-6">
                        <?php
                        /* GhostHtml::a(
                          UserManagementModule::t('front', "Registration"), ['/user-management/auth/registration']
                          ) */
                        ?>
                    </div>
                    <div class="col-sm-6 text-right">
                        <?php
                        /* GhostHtml::a(
                          UserManagementModule::t('front', "Forgot password ?"), ['/user-management/auth/password-recovery']
                          ) */
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
                                        <h3>Welcome to Admin Portal</h3>
                                        <div class="tbl">
                                            <div class="tbl-cell">
                                                <img src="<?= Url::to('themes/pcdf/assets/images/pcdf_smiley.png') ?>" alt="" class="img-responsive"/>
                                            </div>
                                            <div class="tbl-cell">
                                                <h4>Pradeshik Cooperative Dairy Federation, Uttar Pradesh</h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <h3>About Us</h3>
                                        <p class="text-justify">PCDF was formed in 1962 with the aim to develop organized dairying in the State on Cooperative lines PCDF's is a cohesive body that successfully does away with the exploitative forces of years to years-the Middlemen.Therefore a direct link is established between the producer and the ultimate consumer . This Apex Milk Cooperative draws its inherent strength from the farmers committed participation , and injects corporate skills and dynamic professionalism into what is fundamentally a traditional institution.</p>
                                        <a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in"></i> Login</a>
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

<?php
if (!empty($model->getErrors())) {
    $script = "
        $('#loginModal').modal('show');";
    $this->registerJs($script, View::POS_READY, 'login-code');
}
$css = <<<CSS
CSS;

$this->registerCss($css);
?>