<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Process for Payment Disburse';
$action = Url::to(['bank-payment']);
$bmc_info = '';
if (!empty($searchModel)) {
    if (is_array($searchModel->bmc_code)) {
        $mcc_data = $searchModel->mccPlantCode;
        $bmc_info = $mcc_data->mcc_plant_code . ' > ' . $mcc_data->name . ' > ';
    } else {
        $bmc_data = $searchModel->bmcCode;
        $bmc_info = $bmc_data->bmc_code . ' > ' . $bmc_data->bmc_name . ' > ';
    }
    $bmc_info .= Yii::$app->general->getforeignkey($searchModel->customerType, 'customer_desc') . ' > ' .
            (($searchModel->billing_type == 'remuneration') ? Yii::$app->controls->view_date($searchModel->from_datetime) . ' to ' . Yii::$app->controls->view_date($searchModel->to_datetime) :
            Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($searchModel->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($searchModel->paymentCycleCode, 'to_date')));
}
$title = ($searchModel->billing_type == 'remuneration' ? Yii::t('app', 'Remuneration Payment Disburse : Step 2') : Yii::t('app', 'Vendor Payment Disburse : Step 2') ) . ' ' . ' (' . $bmc_info . ')';
$recovery_from_other_vendor = ($searchModel->billing_type != 'remuneration' && isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['recovery_from_other_vendor']) && Yii::$app->session->get('unionConfig')[$searchModel->union_code]['recovery_from_other_vendor'] == 1) ? TRUE : FALSE;
?>
<div class="tbl-member-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $title ?> 
            <div id="total-payment">
                Total Payable :: <?= $pay_amount ?>
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
                <?= Html::activeHiddenInput($searchModel, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($searchModel, 'union_code'); ?>
                <?= Html::activeHiddenInput($searchModel, 'mcc_plant_code'); ?>
                <?php if (is_array($searchModel->bmc_code)) { ?>
                    <?php foreach ($searchModel->bmc_code as $bmc_code) { ?>
                        <?= Html::activeHiddenInput($searchModel, 'bmc_code[]', ['value' => $bmc_code]); ?>
                    <?php } ?>
                <?php } else { ?>
                    <?= Html::activeHiddenInput($searchModel, 'bmc_code'); ?>
                <?php } ?>
                <?= Html::activeHiddenInput($searchModel, 'customer_type'); ?>
                <?php //foreach ($searchModel->dcs_code as $dcs_code) { ?>
                <?php //Html::activeHiddenInput($searchModel, 'dcs_code[]', ['value' => $dcs_code]); ?>
                <?php //} ?>
                <div class="col-sm-2">
                    <?php
                    $allow_disburse_without_release = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['allow_disburse_without_release']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['allow_disburse_without_release'] : 0;
                    if ($allow_disburse_without_release == '1') {
                        echo Yii::$app->dropdown->dropdownStatic('payment_release_type', $searchModel, $form, 'form-group', $searchModel->getAttributeLabel('payment_release_type'), FALSE, 'payment_release_type', FALSE, FALSE, FALSE);
                    } else {
                        $searchModel->payment_release_type = '0';
                        echo Html::activeHiddenInput($searchModel, 'payment_release_type');
                    }
                    ?>
                </div>
                <div class="clearfix"></div>
                <?php
                $attribute = [
                    /* ['class' => 'kartik\grid\CheckboxColumn',
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
                      return ['disabled' => $disabled, 'class' => 'checkbox', 'value' => $model['dcs_code']];
                      }],
                      ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name',
                      'label' => Yii::t('app', 'DCS')
                      ], */
                        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
                        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
                        }, 'filter' => false],
                        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type);
                        }],
                        ['attribute' => 'is_verified',
                        'value' => function($model) {
                            return ($model->is_verified == 0) ? 'Not Verified' : ($model->is_verified == 1 ? 'Verified' : 'Rejected');
                        }
                    ],
                        ['attribute' => 'bank_name'],
                        ['attribute' => 'branch_name'],
                        ['attribute' => 'bank_account_no'],
                        ['attribute' => 'ifsc'],
                        ['attribute' => 'amount', 'pageSummary' => true, 'value' => 'amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'addition', 'pageSummary' => true, 'value' => 'addition',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'deduction', 'pageSummary' => true, 'value' => 'deduction',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'previous_hold', 'pageSummary' => true, 'value' => 'previous_hold',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'previous_due', 'pageSummary' => true, 'value' => 'previous_due',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'hold_amount', 'pageSummary' => true, 'value' => 'hold_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'adjust_amount', 'pageSummary' => true, 'value' => 'adjust_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                        ['attribute' => 'adjust_recovery', 'visible' => $recovery_from_other_vendor, 'pageSummary' => true,
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat()
                    ],
                        ['attribute' => 'recovery', 'visible' => $recovery_from_other_vendor, 'pageSummary' => true,
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat()
                    ],
                        ['attribute' => 'final_pay', 'pageSummary' => true, 'value' => function($model) {
                            //return round($model->final_pay);
                            return $model->final_pay;
                        },
                        'contentOptions' => ['class' => 'final-amount'],
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                ];

                $grid_option = [
                    'id' => 'bank-payment-final-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
                ?>
                <div class="clearfix"></div>
                <div class="col-md-12" >    
                    <?= Html::button(Yii::t('app', 'Disburse'), ['class' => 'btn btn-primary disburse', 'name' => 'member']); ?>
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
    /* $('.select-on-check-all').attr('checked','checked');
     $('.checkbox').not(':disabled').attr('checked','checked');     
     SumAmount();
     $('.select-on-check-all').change(function() {
     SumAmount();
      });
         $('.checkbox').change(function() {
     SumAmount();
      }); */
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
    
//        $('#otp-form').submit();

        var postVspDisbData = $('#otp-form').serializeArray();
        $('#loadercontent').show();
        $('#pageloader').show();
        $.ajax({
            type: 'post',
            url: '" . $action . "',
            data: postVspDisbData,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {  
                    window.location=data.url;
                } else {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+data.msg+\"</span></div></div>\");
                }
            },
            error:function(data){
                $('#loadercontent').hide();
                $('#pageloader').hide();
                return false;
                    //alert('Your data has not been submitted..Please try again');
            }
        });




   // $('#error-summary').hide();
      //sendotp();
    });
  function sendotp(){
          $.ajax({
                                type: 'post',
                                url: '" . Url::to(['tbl-member-payment/send-otp']) . "',
                                data: 'union_code=" . $searchModel->union_code . "',
                                success: function (data) {
                                    $('#loadercontent').hide();
                                    $('#pageloader').hide();
                                    var obj = $.parseJSON(data);
                                    if (obj.status == 'success')
                                    {
                                       $('#OtpModal').modal('toggle'); 
                                    }else{
                                       bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                                    }
                                }
                            });  
}
    ";
$this->registerJs($script, View::POS_END, 'data-export-script');
?>
<?php
$script = "    
    $('.verify').on('click',function(){     
       var otp=$('#tblvsppayment-otp_code').val();
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
$this->registerJs($script, View::POS_END, 'otp-verify-script');
?>