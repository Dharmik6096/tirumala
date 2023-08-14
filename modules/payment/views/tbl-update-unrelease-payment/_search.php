<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-member-payment-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'validateOnBlur' => false,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-12 mt10 padding-left-0">
        <div class="col-sm-3" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code') . ' *'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('payment_type', $model, $form, '', 'Payment Type *'); ?>
        </div>
        <div class="col-sm-2 cust-type" id="customer-type-wrapper">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-bmc_code', 'customer_type', 'Customer Type *', FALSE); ?>
        </div>
        <div class="col-sm-1 form-group mt23">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<?php
$script = " 
    $('.cust-type').css('display','none'); 
    hideShowDiv();
    $('#tblvsppayment-payment_type').on('change', function() {
       hideShowDiv();
    });
    function hideShowDiv() {
        $('#customer-type-wrapper').hide();
         var payment_type = $('#tblvsppayment-payment_type').val();
        if (payment_type === 'VENDOR') {
            $('#customer-type-wrapper').show();
        }
    }
";
$this->registerJs($script, View::POS_END, 'check-mcc-type');
?>