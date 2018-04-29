<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Update Collection Status for Society: ').$model->dcsCode->dcs_name;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'fieldConfig' => [
                ]]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-3">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', false, date('d-m-Y')); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'remarks')->textArea(['maxlength' => true]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
