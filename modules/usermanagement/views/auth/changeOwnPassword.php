<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */
$this->title = Yii::t('app', 'Change own password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success text-center">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php
        $form = ActiveForm::begin([
                    'id' => 'user',
                    'validateOnBlur' => false,
        ]);
        ?>

        <div class="row">
            <?php if ($model->scenario != 'restoreViaEmail'): ?>            
                <div class="col-sm-2">
                    <?= $form->field($model, 'current_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                </div>
            <?php endif; ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
            </div>
            <!-- <div class="clearfix"></div> -->
            <div class="col-sm-2 padding_top_20">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>