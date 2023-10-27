<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? false : true;
$class = $type == 'create' ? '' : 'disabled';
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
        <?= Yii::$app->dropdown->dropdownStatic('org_type_shift_time', $model, $form, '', $model->getAttributeLabel('org_type'), $readonly, 'org_type', false, false, false); ?>
    </div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblshifttimeandroid-union_code', 'plant_code', $model->getAttributeLabel('plant'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 default_hide from_hide">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblshifttimeandroid-plant_code', 'mcc_plant_code', $model->getAttributeLabel('MCC'), false, '', $readonly); ?>
        </div>
        <div class="col-sm-2 default_hide from_hide">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblshifttimeandroid-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('BMC'), false, '', '', $readonly); ?>
        </div>
    <?php } else { ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'plant_code')->textInput(['value' => isset($model->plantCode) ? $model->plantCode['name'] : 'N/A', 'readonly' => $readonly,])->label('PLANT') ?>
        </div>
        <?php if ($model->org_type == 'BMC') { ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'plant_code')->textInput(['value' => isset($model->plantCode) ? Yii::$app->general->getforeignkey($model->plantCode, 'name') : 'N/A', 'readonly' => $readonly,])->label('PLANT') ?>
            </div>
            <?php if ($model->org_type == 'BMC') { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'mcc_plant_codes')->textInput(['value' => isset($model->mccName) ? Yii::$app->general->getforeignkey($model->mccName, 'name') : 'N/A', 'readonly' => $readonly,])->label('MCC') ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'bmc_code')->textInput(['value' => isset($model->bmcCode) ? Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') : 'N/A', 'readonly' => $readonly,])->label('BMC') ?>
                </div>
            <?php } ?>
            <?php if ($model->org_type == 'MCC') { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'mcc_plant_codes')->textInput(['value' => ($model->org_type === 'MCC' && isset($model->mccCode)) ? Yii::$app->general->getforeignkey($model->mccCode, 'name') : 'N/A', 'readonly' => $readonly,])->label('MCC') ?>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if ($model->org_type == 'MCC') { ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'mcc_plant_codes')->textInput(['value' => ($model->org_type === 'MCC' && isset($model->mccCode)) ? $model->mccCode['name'] : 'N/A', 'readonly' => $readonly,])->label('MCC') ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="col-sm-2 hide-qty-no">
        <?= Yii::$app->dropdown->dropdownStatic('collection_type_shift_time', $model, $form, '', $model->getAttributeLabel('collection_type'), $readonly, 'collection_type', false, false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'm_start_time')->textInput(['type' => 'time']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'e_start_time')->textInput(['type' => 'time']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'm_lock_time')->textInput(['type' => 'time']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'e_lock_time')->textInput(['type' => 'time']) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('boolean_value', $model, $form, 'form-group', $model->getAttributeLabel('date_shift_enable'), false, 'date_shift_enable', false); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'grace_hr')->textInput() ?>
    </div>
    <div class="col-sm-2 padding_top_20">
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
    hideSectionManage($('#tblshifttimeandroid-org_type').val());
    
    $('#tblshifttimeandroid-org_type').on('change', function(){
        var org_type = $(this).val();
        hideSectionManage(org_type);
    });

    function hideSectionManage(type){
        if(type == 'MCC'){
            $('.field-tblshifttimeandroid-plant_code').parent('div').show();
            $('.field-tblshifttimeandroid-mcc_plant_code').parent('div').show();
            $('.field-tblshifttimeandroid-bmc_code').parent('div').hide();
            $('#tblshifttimeandroid-mcc_plant_code').val('').trigger('change');
            $('#tblshifttimeandroid-bmc_code').val('').trigger('change');
        } else if(type == 'BMC') {
            $('.field-tblshifttimeandroid-plant_code').parent('div').show();
            $('.field-tblshifttimeandroid-mcc_plant_code').parent('div').show();
            $('.field-tblshifttimeandroid-bmc_code').parent('div').show();
        } else {
            $('.field-tblshifttimeandroid-plant_code').parent('div').hide();
            $('.field-tblshifttimeandroid-mcc_plant_code').parent('div').hide();
            $('.field-tblshifttimeandroid-bmc_code').parent('div').hide();
        }
    }
";
$this->registerJs($script, View::POS_END, 'shift-time-android');
?>
