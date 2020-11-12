<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblHeadLoadTransaction */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Head Load Transaction');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin(['options' => [

                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>

<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= $form->field($model, 'from_km', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

    <?= $form->field($model, 'to_km', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

    <?= $form->field($model, 'from_qty', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

    <?= $form->field($model, 'to_qty', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

    <div class="clearfix"></div>

    <?= $form->field($model, 'value', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

    <div class="clearfix"></div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save($button, $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model, 'index'); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

