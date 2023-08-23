<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
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
?>

<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('task_performed_for', $model, $form, '', $model->getAttributeLabel('task_performed_for'), FALSE, 'task_performed_for'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbltask-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>
    <div class="col-sm-2 default_hide_input location_bmc location_dcs">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbltask-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code') . ' *'); ?>
    </div>  
    <div class="col-sm-2 default_hide_input location_bmc location_dcs">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbltask-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code') . ' *'); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('task_type', $model, $form, 'tbltask-union_code', '', $model->getAttributeLabel('task_type_code')); ?>
    </div>
    <div class="col-sm-2 default_hide_input task_form">
        <?= Yii::$app->dropdown->depend_dropdown('form_type', $model, $form, 'tbltask-task_type_code', '', $model->getAttributeLabel('form_type_code') . ' *'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-4 default_hide_input location_dcs">
        <?= Yii::$app->dropdown->all_routes($model, $form, 'tbltask-plant_code,tbltask-mcc_plant_code,tbltask-bmc_code', 'route_code', $model->getAttributeLabel('route_code') . ' *', TRUE); ?>
    </div>
    <div class="col-sm-8 default_hide_input location_dcs">
        <?= Yii::$app->dropdown->route_dcs($model, $form, 'tbltask-route_code', 'dcs_code', $model->getAttributeLabel('dcs_code') . ' *', TRUE); ?>
    </div>
    <div class="col-sm-8">
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->dropdown('user', $model, $form, '', $model->getAttributeLabel('user_code'), FALSE, 'user_code'); ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->controls->date($model, $form, 'start_date', '', FALSE, date('Y-m-d'), FALSE, true); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->dropdownStatic('task_repeat_interval', $model, $form, '', $model->getAttributeLabel('repeat_interval'), FALSE, 'repeat_interval'); ?>
        </div>
        <div class="col-sm-3 default_hide_input repeat_daily repeat_weekly">
            <?= Yii::$app->controls->date($model, $form, 'end_date', '', FALSE, date('Y-m-d', strtotime('+1 day')), FALSE, true); ?>
        </div>
        <div class="col-sm-6 default_hide_input repeat_weekly">
            <?= Yii::$app->controls->weekday_list($model, $form); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'description')->textarea() ?>
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

<?php
$script = "
     showLocation(); 
     showForm(); 
     showInterval(); 

        $('#tbltask-task_performed_for').change(function () {
            showLocation();
        });
        $('#tbltask-form_type_code').change(function () {
            showForm();
        });
        $('#tbltask-repeat_interval').change(function () {
            showInterval();
        });        
        function showLocation() {
            $('.location_dcs').hide();
            $('.location_bmc').hide();
            var location_type = $('#tbltask-task_performed_for').val();
            if(location_type=='DCS'){
               $('.location_dcs').show();
            }else if (location_type=='BMC'){
               $('.location_bmc').show();
            }           
        }  
        function showForm() {
            $('.task_form').hide();
            if($('select#tbltask-form_type_code option').length > 1){
                $('.task_form').show();
            }
        }
        function showInterval() {
           $('.repeat_daily').hide();
            $('.repeat_weekly').hide();
            var repeat_type = $('#tbltask-repeat_interval').val();
            if(repeat_type=='1'){
               $('.repeat_daily').show();
            }else if (repeat_type=='2'){
               $('.repeat_weekly').show();
            } 
        }

";

$this->registerJs($script, View::POS_END, 'task-create-form');
?>
