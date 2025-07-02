<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
?>
<?php
$form = ActiveForm::begin([
    'validateOnBlur' => false,
    'validateOnChange' => FALSE,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('organization_latlong_type', $model, $form, 'form-group', $model->getAttributeLabel('customer_type'), $readonly, 'customer_type'); ?>
    </div>
    <?php
    if ($type != 'create') { ?>
        <div class="col-sm-2 <?= $disable ?>">
            <?= $form->field($model, 'name')->textInput(['value' => Yii::$app->general->getField($model, $model->customer_type)]); ?>
        </div>
        <?php
    } ?>
    <?php
    if ($type == 'create') { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblorganizationlatlong-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblorganizationlatlong-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblorganizationlatlong-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblorganizationlatlong-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->customer_code($model, $form, 'tblorganizationlatlong-bmc_code,tblorganizationlatlong-customer_type', 'customer_code_other', $model->getAttributeLabel('customer_code'), FALSE, FALSE, FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->dropdown('latlong_user', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('user_code'), false, 'user_code'); ?>
        </div>
    <?php
    } ?>
    <div class="col-sm-2">
        <?= Html::activeHiddenInput($model, 'customer_code', ['id' => 'customer_code']) ?>
        <?= $form->field($model, 'lat_long')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
$(document).ready(function(){
    $('.create_fields').hide();
    hideShowField();
    $('.field-tblorganizationlatlong-union_code').parent('div').hide();
    setCode();
    $('#tblorganizationlatlong-customer_type').on('change', function(){
        hideShowField();
    });

    function hideShowField(){
        var moduleName = $('#tblorganizationlatlong-customer_type').val();
        $('.field-tblorganizationlatlong-union_code').parent('div').show();
        $('.create_fields').hide();
        if(moduleName == 'PLANT'){
            $('#tblorganizationlatlong-plant_code').closest('.create_fields').show();
        } else if (moduleName == 'MCC'){
            $('#tblorganizationlatlong-plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-mcc_plant_code').closest('.create_fields').show();
        } else if (moduleName == 'BMC'){
            $('#tblorganizationlatlong-plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-mcc_plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-bmc_code').closest('.create_fields').show();
        } else if (moduleName == 'DCS'){
            $('#tblorganizationlatlong-plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-mcc_plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-bmc_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-dcs_code').closest('.create_fields').show();
        } else if (moduleName == 'BULKVEN'){
            $('#tblorganizationlatlong-plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-mcc_plant_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-bmc_code').closest('.create_fields').show();
            $('#tblorganizationlatlong-customer_code_other').closest('.create_fields').show();
        } else if (moduleName == 'HOME' || moduleName == 'OFFICE' || moduleName == 'OTHER'){
            $('#tblorganizationlatlong-user_code').closest('.create_fields').show();
            $('.field-tblorganizationlatlong-union_code').parent('div').hide();
        }
    }

    $('#tblorganizationlatlong-plant_code, #tblorganizationlatlong-mcc_plant_code, #tblorganizationlatlong-bmc_code, #tblorganizationlatlong-dcs_code, #tblorganizationlatlong-customer_code, #tblorganizationlatlong-user_code').on('change', function(){ 
        setCode();
    });

    function setCode(){
        var moduleName = $('#tblorganizationlatlong-customer_type').val();
        var plant = $('#tblorganizationlatlong-plant_code').val();
        var mcc = $('#tblorganizationlatlong-mcc_plant_code').val();
        var bmc = $('#tblorganizationlatlong-bmc_code').val();
        var dcs = $('#tblorganizationlatlong-dcs_code').val();
        var customer = $('#tblorganizationlatlong-customer_code_other').val();
        var user = $('#tblorganizationlatlong-user_code').val();
        $('#customer_code').val('');
        if(moduleName == 'PLANT'){
            $('#customer_code').val(plant);
        } else if (moduleName == 'MCC'){
         $('#customer_code').val(mcc);
        } else if (moduleName == 'BMC'){
            $('#customer_code').val(bmc);
        } else if (moduleName == 'DCS'){
            $('#customer_code').val(dcs);
        } else if (moduleName == 'BULKVEN'){
            $('#customer_code').val(customer);
        } else if (moduleName == 'HOME' || moduleName == 'OFFICE' || moduleName == 'OTHER'){
            $('#customer_code').val(user);
        }
    }
})";
$this->registerJs($script, View::POS_END, 'organization-latlong');
?>