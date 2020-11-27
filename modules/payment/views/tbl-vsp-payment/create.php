<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Vendor Payment Process : Step 1';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    //'action' => ['list-payment'],
                    //'method' => 'GET',
                    'validateOnBlur' => false,
                    
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-3" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code', TRUE); ?>
            </div>      
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code', TRUE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvsppayment-bmc_code', 'customer_type', TRUE, FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?php
                $where = json_encode(['data_lock_bmc' => 1, 'billing_lock_bmc' => 0]);
                echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                echo Html::hiddenInput('member_billing_lock_check', 1, ['id' => 'member_billing_lock_check']);
                ?>
                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-bmc_code,tblvsppayment-customer_type,applicable_for,data_lock_bmc,member_billing_lock_check', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
            </div>

            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>   
                    <?= Yii::$app->controls->cancel(); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
 /* $('#tblvsppayment-customer_type').on('change',function(){
     var customer_type= $(this).val();
    var where_data_lock='';     
    if(customer_type =='DCS'){
       where_data_lock={'data_lock_bmc':1,'billing_lock_bmc':0,'billing_lock_member':1};
     }else{
         where_data_lock={'data_lock_bmc':1,'billing_lock_bmc':0};
     }
      $('#data_lock_bmc').val(JSON.stringify(where_data_lock));
});*/     

  $('#tblvsppayment-payment_cycle_code').on('change',function(){
     var payment_cycle_code= $(this).val();
     if(payment_cycle_code !=''){
     var union_code= $('#tblvsppayment-union_code').val();
     var bmc_code= $('#tblvsppayment-bmc_code').val();
     var customer_type= $('#tblvsppayment-customer_type').val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-payment-processed']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'customer_type' : customer_type,'union_code':union_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var message = data.message;
               if(message != ''){
                    bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-info-circle\'></i></div><span>'+message+'</span>');
               }   
            }
        });
      }
}); 
";
$this->registerJs($script, View::POS_END, 'check-payment-cycle-processed');
?>