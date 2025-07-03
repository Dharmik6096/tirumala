<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tblproductsalerateapplicabilitysearch';
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
    <?= Yii::$app->dropdown->dropdownStatic('apply_for', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', 'Rate For', FALSE, 'is_member_rate', FALSE) ?> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($model, $form, $depend . '-bmc_code', 'applicable_for', $model->getAttributeLabel('applicable_for'), FALSE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_code($model, $form, 'tblproductsalerateapplicabilitysearch-bmc_code,tblproductsalerateapplicabilitysearch-applicable_for', 'applicable_code', $model->getAttributeLabel('applicable Name'), FALSE); ?>
    </div>
    <div class="clearfix"></div>

    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
        $('#tblproductsalerateapplicabilitysearch-is_member_rate').on('change', function(){
            hideType();
        });
        
    function hideType(){
            var flag = $('#tblproductsalerateapplicabilitysearch-is_member_rate').val();
                console.log(flag);
            if(flag == 1){
                $('#tblproductsalerateapplicabilitysearch-applicable_for').val('DCS');
                $('#tblproductsalerateapplicabilitysearch-applicable_for').parent('div').parent().hide();
                $('#tblproductsalerateapplicabilitysearch-applicable_for').trigger('change');
            }else{
                $('#tblproductsalerateapplicabilitysearch-applicable_for').parent('div').parent().show(); 
                $('#tblproductsalerateapplicabilitysearch-applicable_for').val('');
            }
        
    }
    $('#tblproductsalerateapplicabilitysearch-applicable_for').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        var flag = $('#tblproductsalerateapplicabilitysearch-is_member_rate').val();
        if(flag == '1'){
            $('#tblproductsalerateapplicabilitysearch-applicable_for').val('DCS');
            $('#tblproductsalerateapplicabilitysearch-applicable_for').parent('div').parent().hide();
            $('#tblproductsalerateapplicabilitysearch-applicable_for').trigger('change');
        }else{
            $('#tblproductsalerateapplicabilitysearch-applicable_for').parent('div').parent().show();    
            $('#tblproductsalerateapplicabilitysearch-applicable_for').val('');
        }
    });
    $(document).ready(function() {
        hideType();
    
    });
";
$this->registerJs($script, View::POS_END, 'sale-rate-applicability-script');
?>