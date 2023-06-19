<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Process for Payment Disburse';
?>
<div class="tbl-transporter-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= 'Transporter Payment Disburse : Step 2' ?> 
            <div id="total-payment">
                Total Payable :: 0.00
            </div>
        </div>
        <div class="panel-body">
            <div class="grid-search no-effect" >
                <?php
                $form = ActiveForm::begin(['options' => [
                                'class' => 'popup-form',
                                'id' => 'otp-form',
                            ],
                            'action' => Url::to(['bank-payment'])
                ]);
                ?>   
                <?= Html::activeHiddenInput($searchModel, 'transporter_payment_cycle'); ?>
                <?= Html::activeHiddenInput($searchModel, 'union_code'); ?>
                <?php foreach ($searchModel->transporter_payment_code as $transporter_payment_code) { ?>
                    <?= Html::activeHiddenInput($searchModel, 'transporter_payment_code[]', ['value' => $transporter_payment_code]); ?>
                <?php } ?>
                <?php
                $attribute = [
                    ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                        'checkboxOptions' => function($model) {
                    $disabled = FALSE;
                    if ($model->ifsc == '' || $model->bank_account_no == '') {
                        $disabled = true;
                    } else if ($model->is_verified == 2) {
                        $disabled = true;
                    } else if (Yii::$app->session->get('makerChecker') == 1 && $model->is_verified == 0) {
                        $disabled = true;
                    }
                    return ['disabled' => $disabled, 'class' => 'checkbox', 'value' => $model['transporter_payment_code']];
                }],
                    ['attribute' => 'transporter_code', 'value' => 'transporterCode.transporter_name'],
                    ['attribute' => 'bmc_code', 'value' => 'bmcCode.bmc_name'],
                    ['attribute' => 'is_verified',
                        'value' => function($model) {
                            return ($model->is_verified == 0) ? 'Not Verified' : ($model->is_verified == 1 ? 'Verified' : 'Rejected');
                        }
                    ],
                    ['attribute' => 'bank_name'],
                    ['attribute' => 'branch_name'],
                    ['attribute' => 'bank_account_no'],
                    ['attribute' => 'ifsc'],
                    ['attribute' => 'total_amount', 'pageSummary' => true, 'value' => 'total_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'total_deduction', 'pageSummary' => true, 'value' => 'total_deduction',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'adjust_amount', 'pageSummary' => true, 'value' => 'adjust_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'final_amount', 'pageSummary' => true, 'value' => 'final_amount',
                        'contentOptions' => ['class' => 'final-amount'],
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                ];

                $grid_option = [
                    'id' => 'tp-bank-payment-final-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
                ?>
                <div class="clearfix"></div>
                <div class="col-md-12" >    
                    <?= Html::button(Yii::t('app', 'Transporter Payment'), ['class' => 'btn btn-primary disburse', 'name' => 'tp']); ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'payment-disburse'); ?> 
                </div>
                <?= $this->render('/tbl-member-payment/verify-otp', ['model' => $searchModel, 'form' => $form]) ?>
                <div class="clearfix"></div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
   $(document).ready(function(){ 
     $('.kv-panel-before').hide();
     $('.select-on-check-all').attr('checked','checked');
     $('.checkbox').not(':disabled').attr('checked','checked');     
     SumAmount();
     $('.select-on-check-all').change(function() {
     SumAmount();
      });
         $('.checkbox').change(function() {
     SumAmount();
      });
 function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.checkbox').each(function() {
  if($(this).is(':checked')){
          var amt = $(this).closest('tr').find('.final-amount').text();
          amt=amt.replace(',','');
          total =  parseFloat(total) + parseFloat(amt);  
          }
        }).get();
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }      
    });
    $('.disburse').on('click',function(){
    $('#error-summary').hide();
      sendotp();
    });
  function sendotp(){
          $.ajax({
                                type: 'post',
                                url: '" . Url::to(['tbl-member-payment/send-otp']) . "',
                                data: 'union_code=" . $searchModel->union_code . "',
                                success: function (data) {
                                    var obj = $.parseJSON(data);
                                    if (obj.status == 'success')
                                    {
                                    $('#loadercontent').hide();
                                  $('#pageloader').hide();
                                       $('#OtpModal').modal('toggle'); 
                                    }else{
                                       bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                                    }
                                }
                            });  
}
    ";
$this->registerJs($script, View::POS_END, 'tp-otp-sent-script');
?>
<?php
$script = "    
    $('.verify').on('click',function(){     
       var otp=$('#tbltransporterpayment-otp_code').val();
       if(otp!=''){
          $.ajax({
                                type: 'post',
                                url: '" . Url::to(['tbl-member-payment/verify-otp']) . "',
                                data: 'otp_code='+otp,
                                success: function (data) {
                                    var obj = $.parseJSON(data);
                                    if (obj.status == 'success')
                                    {
                                        $('#otp-form').submit();
                                         $('#loadercontent').show();
                           $('#pageloader').show();
                                    }else{                                    
                          $('#error-summary ul').html('');                  
                         $('#error-summary ul').append('<li>' + obj.message + '</li>');      
                        $('#error-summary').show();
                                    }
                                }
                            });
                            }else{
                           $('#error-summary ul').html('');                  
                         $('#error-summary ul').append('<li>OTP Can not be blank.</li>');      
                        $('#error-summary').show();              

}
    });
    ";
$this->registerJs($script, View::POS_END, 'tp-otp-verify-script');
?>