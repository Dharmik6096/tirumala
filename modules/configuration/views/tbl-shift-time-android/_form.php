<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
    <div class="col-sm-12">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('org_type_shift_time', $model, $form, '', $model->getAttributeLabel('org_type'), $readonly, 'org_type', false, false, false); ?>
        </div>
        <?php if ($type == 'create') { ?>
            <div class="col-sm-2 default_hide from_hide">
                <?= Yii::$app->dropdown->dropdown('plant_list', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('PLANT'), $readonly); ?>
            </div>
            <div class="col-sm-2 default_hide from_hide">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblshifttimeandroid-plant_code', 'mcc_plant_code', $model->getAttributeLabel('MCC'), false, '', $readonly); ?>
            </div>
            <div class="col-sm-2 default_hide from_hide">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblshifttimeandroid-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('BMC'), false, '', '', $readonly); ?>
            </div>
        <?php } else { ?>
            <?php if ($model->org_type == 'BMC') { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('bmc_list', $model, $form, 'form-group col-sm-2', Yii::t('app', 'BMC'), $readonly); ?>
                </div>
            <?php } ?>
            <?php if ($model->org_type == 'MCC') { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('mcc_list', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('MCC'), $readonly); ?>
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
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->cancel($model); ?>
            </div>
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
