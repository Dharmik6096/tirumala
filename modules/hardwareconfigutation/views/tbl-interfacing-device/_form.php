<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hardwareconfigutation\models\TblInterfacingDevice */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Interfacing Device');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

    <?php
    $form = ActiveForm::begin(['options' => [

                    'class' => 'save-form',
                    'field-class' => 'form-group col-sm-3'
                ],
                'validateOnBlur' => false,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
            ]]);
    ?>
<div class="panel-body">
    <div class="panel-subheading">
        <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">

        <?= $form->field($model, 'device_name', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
         
        <?= Yii::$app->dropdown->dropdown('device_manufacturer', $model, $form, '', 'Device Manufacturer'); ?>    
                  
        <?= $form->field($model, 'device_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getDeviceType(),['prompt' => 'Select Device Type']); ?>
        
        <?= $form->field($model, 'is_snf', ['options' => ['class' => 'form-group col-sm-3'], 'template' => '{label}<div class="checkbox">{input}</div>{error}{hint}',])->checkbox(); ?>
          
        <div class="clearfix"></div>    
        <?= $form->field($model, 'baud_rate', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getBaurdRate(),['prompt' => 'Select Baurd Rate']); ?>    
        
        <?= $form->field($model, 'bit_rate', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getBitRate(),['prompt' => 'Select Bit Rate']); ?>    
        
        <?= $form->field($model, 'parity', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getParity(),['prompt' => 'Select Parity']); ?>    
        
        <?= $form->field($model, 'reading_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getReadingType(),['prompt' => 'Select Reading Type']); ?>
        <div class="clearfix"></div>    
        <?= $form->field($model, 'split_char', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
            
        <?= $form->field($model, 'discard_char', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'incoming_data_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($model->getIncomingType(),['prompt' => 'Select Incoming Data Type']); ?>
            
        <?= $form->field($model, 'length', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput() ?>
        <div class="clearfix"></div>        
        <?= $form->field($model, 'reg_expression', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
            
        <?= $form->field($model, 'start_char', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
            
        <?= $form->field($model, 'end_char', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tare', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true]) ?>
        <div class="clearfix"></div>    
        <?= $form->field($model, 'stop_bit', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput() ?>

        <?= Yii::$app->controls->active($model, $form); ?>
        </div>
        </div>
</div>

<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model); ?>
</div>

    <?php ActiveForm::end(); ?>

