<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

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
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('location_type', $model, $form, '', $model->getAttributeLabel('location_type'), false, 'location_type', FALSE, FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcomplain-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcomplain-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcomplain-mcc_plant_code', 'bmc_code', Yii::t('app', 'bmc_code'), FALSE); ?>
    </div>  
    <div class="col-sm-2 default_hide from_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblcomplain-bmc_code', 'dcs_code', Yii::t('app', 'dcs_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'contact_person')->textInput(['data-val' => $model->contact_person]) ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'mobile_no')->textInput(['data-val' => $model->mobile_no]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('complain_type', $model, $form, '', $model->getAttributeLabel('complain_type_code'), false, 'complain_type_code'); ?>
        <?php echo Html::hiddenInput('TblComplain[complain_for]', '', ['id' => 'complain_for']); ?>
    </div>
    <!--    <div class="col-sm-3">
            <?// Yii::$app->dropdown->mcc_dcs_asset_sr_no($model, $form, 'tblcomplain-union_code', 'asset_code', $model->getAttributeLabel('asset_code'), FALSE, '', FALSE, TRUE); ?>               
        </div>-->
    <div class="col-sm-2">
        <?= $form->field($model, 'serial_number')->textInput(['readonly' => true, 'data-val' => $model->serial_number]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'affects_data', ['checkboxTemplate' => "<div class='checkbox mb0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>
    <div class="col-sm-2 mt20">
        <?= $form->field($model, 'physical_damage', ['checkboxTemplate' => "<div class='checkbox mt0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-2">
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
    $('#tblcomplain-location_type').on('change', function(){
        var location_type = $(this).val();
        hideSectionManage(location_type);
    });
//    $('#tblcomplain-complain_type_code').on('change', function() {
//        var complainType = $(this).val();
//        $.ajax({
//            type: 'post',
//            url: '" . Url::to(['get-asset']) . "',
//            data: {'comaplin_type' : complainType},
//            success: function(data) {
//                if(dada.status == 'success') {
//                    $('#complain_for').val(data.compain_name);
//                }
//            },
//        });
//    });
    function hideSectionManage(type){
        if(type == 'plant'){
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').hide();
            $('.field-tblcomplain-bmc_code').parent('div').hide();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
        } else if(type == 'bmc') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').hide();
        } else if(type == 'dcs') {
            $('.field-tblcomplain-plant_code').parent('div').show();
            $('.field-tblcomplain-mcc_plant_code').parent('div').show();
            $('.field-tblcomplain-bmc_code').parent('div').show();
            $('.field-tblcomplain-dcs_code').parent('div').show();
        }
    }
";
$this->registerJs($script, View::POS_END, 'create-complain');
?>