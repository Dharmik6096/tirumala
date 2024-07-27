<?php

use app\components\ActiveForm;
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
                <div>
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'login-form',
                                'validateOnBlur' => false,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                            ])
                    ?>
                    <div class="row">
                        <?php
                        if (!$sentOtp) {
                            $button = 'NEXT';
                            ?>
                            <div class="col-sm-12">  
                                <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
                            </div>
                            <?php
                        } else {
                            $button = 'Reset Password';
                            ?>
                            <?= Html::activeHiddenInput($model, 'username'); ?>
                            <?= Html::activeHiddenInput($model, 'mobile_no'); ?>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'otp_code')->textInput() ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'password')->textInput() ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'repeat_password')->textInput() ?>
                            </div>
                        <?php } ?>
                        <div class="col-sm-12 d-grid">
                            <?= Html::submitButton(Yii::t('app', $button), ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                        <p class="center_text mt-3">- OR -</p>
                        <div class="col-sm-12">
                            <?php echo Html::a('Login', ['/user-management/auth/login'], ['title' => 'Click for Redirect Login', 'class' => 'btn btn-primary btn-block']); ?>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>