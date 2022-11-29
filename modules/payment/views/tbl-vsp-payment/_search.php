<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-vsp-payment-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code', 'MCC'); ?>
    </div>      
    <div id="single-bmc">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code', 'BMC *'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-bmc_code', 'customer_type', 'Customer Type *'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-bmc_code,tblvsppayment-customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', 'Payment Cycle *'); ?>
        </div>
    </div>
    <div id="multiple-bmc">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'p_bmc_code', 'BMC *', TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-p_bmc_code', 'p_customer_type', 'Customer Type *'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-p_bmc_code,tblvsppayment-p_customer_type,applicable_for,data_lock_bmc', 'p_payment_cycle_code', 'Payment Cycle *'); ?>
        </div>
    </div>
    <div class="form-group padding_top_20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $where = json_encode(['data_lock_bmc' => 1, 'billing_lock_bmc' => 0]);
    echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
    echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
    ?>
</div>
<?php
$script = "
     $('#single-bmc').hide();
     $('#multiple-bmc').hide();  
$('#tblvsppayment-mcc_plant_code').on('change',function(){
     var mcc_plant_code= $(this).val();
     if(mcc_plant_code !='' && mcc_plant_code != null){
            $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-mcc-type']) . "',
            data: {'mcc_plant_code' : mcc_plant_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var multiple_bmc = data.multiple_bmc;
               if(multiple_bmc == '1'){
                 $('#single-bmc').hide();
                 $('#multiple-bmc').show();
               }else{
                 $('#multiple-bmc').hide();
                 $('#single-bmc').show();
               }   
            }
        });
      }
});
";
$this->registerJs($script, View::POS_END, 'check-mcc-type');
?>