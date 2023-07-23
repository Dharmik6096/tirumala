<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php
echo $form->errorSummary($model);
$model->task_type_code = 'DD from tbl_task_type';
$model->form_type_code = 'DD from tbl_form_type (when task is of Form Activity)';
$model->user_code = 'DD from user (all mobile app user)';
$model->start_date = 'Task Start Date (current+future)';
$model->repeat_interval = 'DD (Do not Repeat/Daily/Weekly)';
$model->week_days = 'Multi Select Week Days List (when Repeat Weekly)';
$model->end_date = 'Task End Date (future) when Repeat Daily/Weekly';
?>

<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('location_type', $model, $form, '', $model->getAttributeLabel('task_performed_for'), FALSE, 'task_performed_for'); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbltask-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbltask-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbltask-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>  

    <div class="col-sm-2">
        <?= $form->field($model, 'route_code')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbltask-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?php //Yii::$app->dropdown->dropdown('complain_type', $model, $form, '', $model->getAttributeLabel('complain_type_code'), $disabled, 'complain_type_code');  ?>
        <?= $form->field($model, 'task_type_code')->textInput() ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'form_type_code')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'user_code')->textInput(['maxlength' => true]) ?>
    </div>


    <div class="col-sm-2">
        <?= $form->field($model, 'start_date')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'repeat_interval')->textInput() ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'week_days')->textInput() ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'end_date')->textInput() ?>
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


