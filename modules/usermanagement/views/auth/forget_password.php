<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$readOnly = $sentOtp;
$class = !$sentOtp ? 'for-pw-in' : 'for-pw-ot';
?>

<div class="panel panel-defaultasd panel-main <?= $class ?>">
    <div class="modal-header">
        <h4 class="modal-title"><?= Yii::t('app', 'ACCOUNT RECOVERY') ?></h4>
    </div>
    <div class="panel-body">
        <div id="login-container">
            <div class="animation-fadeInQuickInv">
                <div class="form-horizontal">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'login-form',
                                'options' => ['autocomplete' => 'off'],
                                'validateOnBlur' => true,
                                //'enableAjaxValidation' => TRUE,
                                'fieldConfig' => [
                                    'template' => "<div class = 'text-center'><div class='col-xs-12'>{error}</div></div>{beginWrapper}{label}{input}\n{endWrapper}",
                                    'wrapperOptions' => ['class' => 'col-xs-12  padding_left_15 padding_right_15']
                                ],
                            ])
                    ?>
                    <div class="row">
                        <?php
                        if (!$sentOtp) {
                            $button = 'NEXT';
                            ?>
                            <div class="col-sm-12">
                                <?=
                                        $form->field($model, 'username', ['template' => '<div class = "text-center"></div>{beginWrapper}{label}{input}{error}{endWrapper}'])
                                        ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'autocomplete' => 'off'])
                                ?>
                            </div>
                            <?php
                        } else {
                            $button = 'Reset Password';
                            ?>
                            <?= Html::activeHiddenInput($model, 'username'); ?>
                            <?= Html::activeHiddenInput($model, 'mobile_no'); ?>
                            <div class="col-sm-12">
                                <?=
                                        $form->field($model, 'otp_code', [
                                            'template' => '<div class = "text-center"></div>{beginWrapper}{label}{input}{error}{endWrapper}'
                                        ])
                                        ->textInput(['placeholder' => '', 'autocomplete' => 'off'])
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <?=
                                        $form->field($model, 'password', [
                                            'template' => '<div class = "text-center"></div>{beginWrapper}{label}{input}{error}{endWrapper}'
                                        ])
                                        ->passwordInput(['placeholder' => '', 'autocomplete' => 'off'])
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <?=
                                        $form->field($model, 'repeat_password', [
                                            'template' => '<div class = "text-center"></div>{beginWrapper}{label}{input}{error}{endWrapper}'
                                        ])
                                        ->passwordInput(['placeholder' => '', 'autocomplete' => 'off'])
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-sm-12 mt15">
                            <?=
                            Html::submitButton(
                                    Yii::t('app', $button), ['class' => 'btn btn-primary btn-block']
                            )
                            ?>
                        </div>
                        <div class="col-sm-12">
                            <?php echo Html::a('Login', ['/user-management/auth/login'], ['title' => 'Click for Redirect Login']); ?>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>