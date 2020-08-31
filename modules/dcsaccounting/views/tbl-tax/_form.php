<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPaymentCycle */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="row">   
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('tax_group', $model, $form, 'tbltax-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('tax_group_code')); ?>
        <?php // Yii::$app->dropdown->dropdown('tax_group_code', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('tax_group_code')); ?>
    </div>  
    <div class="col-sm-3">
        <?= $form->field($model, 'tax_name')->textInput() ?>
    </div>  
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

