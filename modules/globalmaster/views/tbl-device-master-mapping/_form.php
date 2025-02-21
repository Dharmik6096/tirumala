<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?php
        $record = [Yii::$app->request->get('id') => Yii::$app->request->get('type') . '(' . Yii::$app->request->get('srno') . ')'];
        echo $form->field($model, 'device_master_code')->dropDownList($record, ['disabled' => true])->label(Yii::t('app', 'device master'));
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('applicability_type', $model, $form, 'form-group', $model->getAttributeLabel('applicability_type'), false, 'applicability_type', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldevicemastermapping-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>
    <div class="col-sm-2 plant_dock">
        <?= Yii::$app->dropdown->depend_dropdown('dock_no', $model, $form, 'tbldevicemastermapping-plant_code', 'form-group col-sm-2', $model->getAttributeLabel('dock_no')); ?>
    </div> 
    <div class="col-sm-2 mcc">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldevicemastermapping-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>  
    <div class="col-sm-2 default_hide dcs">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldevicemastermapping-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>    
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->merge_bmc_dcs($model, $form, 'tbldevicemastermapping-applicability_type,tbldevicemastermapping-mcc_plant_code,tbldevicemastermapping-bmc_code,tbldevicemastermapping-plant_code', 'applicability_code', 'Applicable Name'); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->controls->date($model, $form, 'wef_date', 'form-group col-sm-3', false, false, false); ?>
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
$('.default_hide').hide();
$('.mcc').hide();
$('#tbldevicemastermapping-applicability_type').on('change', function() {
    $('.default_hide').hide();
    $('.mcc').hide();
    if($(this).val() == '2'){
        $('.default_hide').show();
        $('.mcc').show();
        $('#tbldevicemastermapping-dock_no').val('').trigger('change');
        $('.plant_dock').hide();
    }else if($(this).val() == '3'){
        $('.mcc').hide();
        $('.dcs').hide();
        $('.plant_dock').show();
    }else if($(this).val() == '1'){
        $('.mcc').show();
        $('#tbldevicemastermapping-dock_no').val('').trigger('change');
        $('.plant_dock').hide();
    }
});
";
$this->registerJs($script, View::POS_END, 'create-mapping');
?>
