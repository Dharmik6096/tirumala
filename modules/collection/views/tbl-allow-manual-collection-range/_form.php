<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('table_name', $model, $form, 'form-group', $model->getAttributeLabel('table_name'), false, 'table_name', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblallowmanualcollectionrange-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblallowmanualcollectionrange-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblallowmanualcollectionrange-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2 default_hide_input">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblallowmanualcollectionrange-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, '', $readonly); ?>         
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('entry_type_collection', $model, $form, 'form-group', $model->getAttributeLabel('entry_type'), false, 'entry_type', false); ?>
    </div>
    <?= Html::activeHiddenInput($model, 'from_date', ['value' => $model->from_date]) ?>
    <?= Html::activeHiddenInput($model, 'to_date', ['value' => $model->to_date]) ?>

    <div class="col-sm-2 back-from-date">
        <?= Yii::$app->controls->date($model, $form, 'from_date_back', '', date('d-m-Y'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-2 front-from-date">
        <?= Yii::$app->controls->date($model, $form, 'from_date_real', '', date('d-m-Y'), date('d-m-Y'), $readonly); ?> 
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift', true, $readonly, 'from_shift'); ?>
    </div>
    <div class="col-sm-2 back-to-date">
        <?= Yii::$app->controls->date($model, $form, 'to_date_back', '', date('d-m-Y'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-2 front-to-date">
        <?= Yii::$app->controls->date($model, $form, 'to_date_real', '', date('d-m-Y'), date('d-m-Y')); ?> 
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift', true, FALSE, 'to_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('operation_perform', $model, $form, 'form-group', $model->getAttributeLabel('action_perform'), false, 'action_perform', false); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_weight_manual'); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_quality_manual'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remark')->textarea() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    hideSectionManage($('#tblallowmanualcollectionrange-table_name').val()); 
    $('#tblallowmanualcollectionrange-table_name').change(function () {
        var table_name = $(this).val();
        hideSectionManage(table_name);
    });
            
    function hideSectionManage(table){
        if(table == 'tbl_milk_collection'){
            $('.field-tblallowmanualcollectionrange-bmc_code').parent('div').show();
            $('.field-tblallowmanualcollectionrange-dcs_code').parent('div').show();
        } else {
            $('.field-tblallowmanualcollectionrange-bmc_code').parent('div').show();
            $('.field-tblallowmanualcollectionrange-dcs_code').parent('div').hide();
            $('#tblallowmanualcollectionrange-dcs_code').val('').trigger('change');
        }
    }

    updateDateFields($('#tblallowmanualcollectionrange-entry_type').val());
        
    $('#tblallowmanualcollectionrange-entry_type').on('change', function() {
        var entryType = $(this).val();
        updateDateFields(entryType);
        if(entryType !='realtime'){
            $('#tblallowmanualcollectionrange-to_date_real').val('');
            $('.field-tblallowmanualcollectionrange-to_date_real').removeClass('disabled no_pointer');
            $('#tblallowmanualcollectionrange-to_shift').val('').change();
            $('.field-tblallowmanualcollectionrange-to_shift').removeClass('disabled no_pointer');
        }
    });
        
    function updateDateFields(entryType) {
        if (entryType == 'backdate') {
            $('.back-from-date').show();
            $('.front-from-date').hide();
            $('.back-to-date').show();
            $('.front-to-date').hide();
            $('#tblallowmanualcollectionrange-from_date_real').val('');
            $('#tblallowmanualcollectionrange-to_date_real').val('');
        } else {
            $('.back-from-date').hide();
            $('.front-from-date').show();
            $('.back-to-date').hide();
            $('.front-to-date').show();
            $('#tblallowmanualcollectionrange-from_date_back').val('');
            $('#tblallowmanualcollectionrange-to_date_back').val('');
        }
    }    
    
    $('#tblallowmanualcollectionrange-from_date_back, #tblallowmanualcollectionrange-from_date_real').on('change', function() {
        
    var type = $('#tblallowmanualcollectionrange-entry_type').val();
     var fromDate = $('#tblallowmanualcollectionrange-from_date_back').val() || $('#tblallowmanualcollectionrange-from_date_real').val();
        $('#tblallowmanualcollectionrange-from_date').val(fromDate);
        if(type=='realtime'){
            $('#tblallowmanualcollectionrange-to_date_real').val(fromDate);
            $('#tblallowmanualcollectionrange-to_date').val(fromDate);
            $('.field-tblallowmanualcollectionrange-to_date_real').addClass('disabled no_pointer');
        }else{
            $('#tblallowmanualcollectionrange-to_date_real').val('');
            $('.field-tblallowmanualcollectionrange-to_date_real').removeClass('disabled no_pointer');
        }
    });
    $('#tblallowmanualcollectionrange-from_shift').on('change', function() {
        var to_shift_val = $(this).val();
        var type = $('#tblallowmanualcollectionrange-entry_type').val();
        if(type=='realtime'){
            $('#tblallowmanualcollectionrange-to_shift').val(to_shift_val).change();
            $('.field-tblallowmanualcollectionrange-to_shift').addClass('disabled no_pointer');
        }else{
            $('#tblallowmanualcollectionrange-to_shift').val('').change();
            $('.field-tblallowmanualcollectionrange-to_shift').removeClass('disabled no_pointer');
        }
    });
    $('#tblallowmanualcollectionrange-to_date_back, #tblallowmanualcollectionrange-to_date_real').on('change', function() {
        var toDate = $('#tblallowmanualcollectionrange-to_date_back').val() || $('#tblallowmanualcollectionrange-to_date_real').val();
        $('#tblallowmanualcollectionrange-to_date').val(toDate);
    });
";

$this->registerJs($script, View::POS_END, 'manual-create-form');
?>