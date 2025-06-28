<?php

use app\components\ActiveForm;
use yii\web\View;
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('quality_config_process_name', $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('process_name'), false, 'process_name') ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkqualityparamrangesearch-union_code', 'plant_code', Yii::t('app', 'Plant'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkqualityparamrangesearch-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 default_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkqualityparamrangesearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$script = "
    $(document).ready(function () {
        $('.default_hide').hide();
        hideSectionManage($('#tblmilkqualityparamrangesearch-process_name').val());    
        $('#tblmilkqualityparamrangesearch-process_name').change(function () {
            var process_name = $(this).val();
            hideSectionManage(process_name);
        });

        function hideSectionManage(process_name){
                $('.field-tblmilkqualityparamrangesearch-plant_code').parent('div').hide();
                $('.field-tblmilkqualityparamrangesearch-mcc_plant_code').parent('div').hide();
                $('.field-tblmilkqualityparamrangesearch-bmc_code').parent('div').hide();
            if(process_name == 'BMC_MILK_DISPATCH'){
                $('.field-tblmilkqualityparamrangesearch-plant_code').parent('div').show();
                $('.field-tblmilkqualityparamrangesearch-mcc_plant_code').parent('div').show();
                $('.field-tblmilkqualityparamrangesearch-bmc_code').parent('div').show();
            } else { 
                $('.field-tblmilkqualityparamrangesearch-plant_code').parent('div').show();
                $('.field-tblmilkqualityparamrangesearch-mcc_plant_code').parent('div').hide();
                $('.field-tblmilkqualityparamrangesearch-bmc_code').parent('div').hide();
                $('#tblmilkqualityparamrangesearch-mcc_plant_code').val('').trigger('change');
                $('#tblmilkqualityparamrangesearch-bmc_code').val('').trigger('change');
            }
        }
    });
";

$this->registerJs($script, View::POS_END, 'milk-quality-form');
?>