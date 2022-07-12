<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMemberClassification */
/* @var $form yii\widgets\ActiveForm */
if (Yii::$app->session->get('Unions') != '') {
    $selected = Yii::$app->session->get('Unions');
    $model->union_code = !empty($selected) ? $selected : $model->union_code = !empty($selected);
}
$report_type = array('0' => 'Date & Shift Wise', '1' => 'Date Wise', '2' => 'Consolidated');
$report_name = array('101 - Member Collection Detail' => '101 - Member Collection Detail', '108 - Milk Collection Data' => '108 - Milk Collection Data');
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?php echo $form->field($model, 'report_name')->dropdownList($report_name, ['prompt' => 'Select Report']); ?>
    </div>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblgeneratereportparam-union_code', 'plant_code', 'Plant'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblgeneratereportparam-plant_code', 'mcc_code', 'MCC', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblgeneratereportparam-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblgeneratereportparam-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblgeneratereportparam-dcs_code', '', $model->getAttributeLabel('member_code')); ?>
    </div>
    <div class="clearfix"></div> 

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>
    <div class="col-sm-2 report_type_div">
        <?php echo $form->field($model, 'report_type')->dropdownList($report_type, ['prompt' => 'Select Report Type']); ?>
    </div>
    <div class="col-sm-2 originating_type_div">
        <?= Yii::$app->dropdown->dropdownStatic('originating_type', $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('originating_type'), false, 'originating_type') ?> 
    </div>
    <div class="clearfix"></div> 
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
    showHideParam();
    $(document).on('change', '#tblgeneratereportparam-report_name', function() {  
        showHideParam();
    });
    
     function showHideParam(){
       var report =  $('#tblgeneratereportparam-report_name').val();
        if(report == '101 - Member Collection Detail'){
            $('.report_type_div').show();
            $('.originating_type_div').hide();
            $('#tblgeneratereportparam-originating_type').val('');
            $('#tblgeneratereportparam-originating_type').trigger('change');

        }else if(report == '108 - Milk Collection Data'){
            $('.report_type_div').hide();
            $('.originating_type_div').show();
            $('#tblgeneratereportparam-report_type').val('');
        }else {
             $('.report_type_div').hide();
             $('.originating_type_div').hide();
        }
    }
  
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>