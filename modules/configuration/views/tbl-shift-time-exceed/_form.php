<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
?>

<?php
$form = ActiveForm::begin([
    'options' => [],
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('org_type_exceed', $model, $form, '', $model->getAttributeLabel('org_type'), false, 'org_type', false, false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union'), false); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblshifttimeexceed-union_code', 'plant_code', Yii::t('app', 'Plant'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblshifttimeexceed-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC'), false, '', false); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblshifttimeexceed-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), false, '', '', false); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblshifttimeexceed-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, '', false); ?>
    </div>
    <div class="col-sm-2">
        <?php
        echo Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, true);
        ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, true, 'shift_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'standard_time')->widget(MaskedInput::class, ['mask' => '99:99'])->textInput(['readonly' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'exceed_time')->widget(MaskedInput::class, ['mask' => '99:99',]); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>

</div>
<div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
    $('.default_hide').hide();
    hideSectionManage($('#tblshifttimeexceed-org_type').val());
    $('#tblshifttimeexceed-org_type').on('change', function(){
        var org_type = $(this).val();
        hideSectionManage(org_type);
        showStandardTime();
    });
    
    function hideSectionManage(type){
        if(type == 'BMC'){
            $('.field-tblshifttimeexceed-plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-mcc_plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-bmc_code').parent('div').show();
            $('.field-tblshifttimeexceed-dcs_code').parent('div').hide();
            $('#tblshifttimeexceed-dcs_code').val('').trigger('change');
        } else if(type == 'MCC') {
            $('.field-tblshifttimeexceed-plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-mcc_plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-bmc_code').parent('div').hide();
            $('.field-tblshifttimeexceed-dcs_code').parent('div').hide();
            $('#tblshifttimeexceed-bmc_code').val('').trigger('change');
            $('#tblshifttimeexceed-dcs_code').val('').trigger('change');
        } else if(type == 'VLC') {
            $('.field-tblshifttimeexceed-plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-mcc_plant_code').parent('div').show();
            $('.field-tblshifttimeexceed-bmc_code').parent('div').show();
            $('.field-tblshifttimeexceed-dcs_code').parent('div').show();
        } else {
            $('.field-tblshifttimeexceed-plant_code').parent('div').hide();
            $('.field-tblshifttimeexceed-mcc_plant_code').parent('div').hide();
            $('.field-tblshifttimeexceed-bmc_code').parent('div').hide();
            $('.field-tblshifttimeexceed-dcs_code').parent('div').hide();
        }
    }
    
    $('#tblshifttimeexceed-shift_code').on('change', function(){
        showStandardTime();
    });
    
    $('#tblshifttimeexceed-bmc_code').on('change', function(){
        showStandardTime();
    });
    
    $('#tblshifttimeexceed-mcc_plant_code').on('change', function(){
        showStandardTime();
    });
    
    $('#tblshifttimeexceed-dcs_code').on('change', function(){
        showStandardTime();
    });
    
function showStandardTime() {
    var org_type = $('#tblshifttimeexceed-org_type').val();
    var shift_code = $('#tblshifttimeexceed-shift_code').val();
    var code = '';
    if (org_type == 'BMC') {
        code = $('#tblshifttimeexceed-bmc_code').val();
    } else if (org_type == 'MCC') {
        code = $('#tblshifttimeexceed-mcc_plant_code').val();
    } else if (org_type == 'VLC') {
        code = $('#tblshifttimeexceed-dcs_code').val();
    }
    $.ajax({
        url: '" . Url::to(['get-standard-time']) . "',
        data: {'org_type': org_type,'shift_code': shift_code,'code': code},
        method: 'POST',
        success: function(data) {
            var standardTime = data.standard_time.standard_time;
            $('#tblshifttimeexceed-standard_time').val(standardTime).prop('readonly', true);
        },
    });
}

";
$this->registerJs($script, View::POS_END, 'shift-time-exceed');
?>