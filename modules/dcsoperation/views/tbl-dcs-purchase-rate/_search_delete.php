<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tbldcspurchaserateapplicabititysearch';
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
//                'action' => ['delete-map-route'],
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', 'MCC'); ?>
    </div> 
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', 'BMC'); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('rate_cal_for', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('rate_for'), FALSE, 'rate_for', false, 'both') ?> 

    <div class="col-sm-2 hide_rate_cal">
        <?= Yii::$app->dropdown->customer_type($model, $form, $depend . '-bmc_code', 'applicable_for', $model->getAttributeLabel('applicable_for'), FALSE); ?>
    </div> 
    <div class="col-sm-2 hide_rate_cal">
        <?= Yii::$app->dropdown->customer_code($model, $form, $depend . '-bmc_code,' . $depend . '-applicable_for', 'applicable_code', $model->getAttributeLabel('applicable Name'), FALSE); ?>
    </div>
    <div class="col-sm-2  show_rate_cal">
        <?= Yii::$app->dropdown->bmc_society($model, $form, $depend . '-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', 'form-group', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dcsRateChart($model, $form, 'tbldcspurchaserateapplicabititysearch-union_code,tbldcspurchaserateapplicabititysearch-rate_for', 'purchase_rate_code', Yii::t('app', 'Rate Id')); ?>
    </div>
    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
    $('#tbldcspurchaserateapplicabititysearch-rate_for').on('change', function(e){
        // e.preventDefault();
        hideType();
    });
    $('#tbldcspurchaserateapplicabititysearch-bmc_code').on('change', function(e){
        // e.preventDefault();
        hideType();
    });
        
    function hideType(){
    var flag = $('#tbldcspurchaserateapplicabititysearch-rate_for').val();
        if(flag == 'member'){
            $('.hide_rate_cal').hide();
            $('#tbldcspurchaserateapplicabititysearch-applicable_for').val('');
            $('#tbldcspurchaserateapplicabititysearch-applicable_code').val('');
            $('.show_rate_cal').show();
        }else if(flag == 'both'){
             $('.show_rate_cal').hide();
             $('.hide_rate_cal').hide();
             $('#tbldcspurchaserateapplicabititysearch-applicable_for').val('');
             $('#tbldcspurchaserateapplicabititysearch-applicable_code').val('');
             $('#tbldcspurchaserateapplicabititysearch-dcs_code').val('');
        }else{
             $('.show_rate_cal').hide();
                $('#tbldcspurchaserateapplicabititysearch-dcs_code').val('');
             $('.hide_rate_cal').show();
             checkData();
        }
    }
    $(document).ready(function() {
       var flag = $('#tbldcspurchaserateapplicabititysearch-rate_for').val();
    //    $('#tbldcspurchaserateapplicabititysearch-rate_for').val('');
        $('#tbldcspurchaserateapplicabititysearch-applicable_for').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            // event.stopImmediatePropagation();
            checkData();

            // $('#tbldcspurchaserateapplicabititysearch-rate_for').val(flag);
            // $('#tbldcspurchaserateapplicabititysearch-rate_for').trigger('change');
        });
      
    });
    
function checkData(){
    var modelname = 'tbldcspurchaserateapplicabititysearch';
    var fieldName = 'applicable_for';
    var length = $('#'+modelname+'-'+fieldName+' option[value!=\'\']').length;
    if(length == 0) {
        $('#'+modelname+'-'+fieldName).parent('div').parent().hide();
    } else if(length == 1) {
        $('#'+modelname+'-'+fieldName).val('DCS');
        $('#'+modelname+'-'+fieldName).parent('div').parent().hide();
        $('#'+modelname+'-'+fieldName).trigger('select2:select');
        $('#'+modelname+'-'+fieldName).trigger('change');
    } else {
        $('#'+modelname+'-'+fieldName).parent('div').parent().show();               
    }
}
";
$this->registerJs($script, View::POS_END, 'rate-applicability-search-script');
?>