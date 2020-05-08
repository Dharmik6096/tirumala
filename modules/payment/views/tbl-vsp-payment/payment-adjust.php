<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = $title;
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['net_payable'];
        }, $array));
$final_amt = array_sum(array_map(function($array) {
            return $array['net_payable'] + $array['adjust_amount'] - $array['hold_amount'];
        }, $array));

$bmc_info = Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . ' > ' .
        Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') . ' > ' .
        ($model->billing_type == 'remuneration') ? Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime) :
        Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>  
            <div id="total-payment">
                Total Payable :: <?= $final_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        $attribute = [
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
                }, 'filter' => false],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type);
                }],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'total_qty', 'value' => 'total_qty',
                'pageSummary' => true
            ],
            ['attribute' => 'amount', 'value' => 'amount',
                'pageSummary' => true
            ],
            ['attribute' => 'addition', 'value' => 'addition',
                'pageSummary' => true
            ],
            ['attribute' => 'deduction', 'value' => 'deduction',
                'pageSummary' => true
            ],
            ['attribute' => 'previous_hold', 'pageSummary' => true
            ],
            ['attribute' => 'previous_due', 'pageSummary' => true
            ],
            ['attribute' => 'final_pay', 'value' => 'net_payable',
                'pageSummary' => true,
                'contentOptions' => ['class' => 'final-amount'],
            ],
            ['attribute' => 'hold_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
            return Html::activeHiddenInput($model, 'vsp_payment_code[' . $index . ']', ['value' => $model->vsp_payment_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
        },
            ],
            ['attribute' => 'adjust_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
            return Html::activeHiddenInput($model, 'vsp_payment_code[' . $index . ']', ['value' => $model->vsp_payment_code]) . $form->field($model, 'adjust_amount[' . $index . ']')->textInput(['value' => $model->adjust_amount, 'class' => 'number-validate adjust-amount cal-amount form-control',])->label(FALSE);
        },
            ],
            ['attribute' => 'net_payable',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
            return $form->field($model, 'net_payable[' . $index . ']')->textInput(['class' => 'number-validate net-amount form-control', "disabled" => TRUE, 'value' => $model->final_pay])->label(FALSE);
        },
            ],
            ['attribute' => 'adjust_remark',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
            return $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $model->adjust_remark])->label(FALSE);
        },
            ],
        ];

        $grid_option = [
            'id' => 'vsp-payment-adjust-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-val' => $model->vsp_payment_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-vsp-payment/bill-head', 'id' => $model->vsp_payment_code], $options);
                },
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
                ?>
            </div>
            <div class="panel-footer" >

                <?php
                if (!empty($dataProvider->getModels())) {
                    echo Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust']);
                }
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'create'); ?> 
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div id='bill_head_view'></div>
        <?php
        $script = "$('#adjust').click(function() {
            var data_ok=1;
          $('.net-amount').each(function() {
             var netamount =  parseFloat($(this).val());
         if(netamount<0){
            data_ok=0;
            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
            });
            return false; 
          }
        });
    if(data_ok==1){
        $('#payment-adjust').submit();
    }
});
";
        $script .=" $('.cal-amount').on('blur',function(){     
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
  
        if(adjust == '' ||  isNaN(adjust)){
        adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
        hold=0;
        }
        var net = final + adjust - hold;  
         parent.find('.net-amount').val(net.toFixed(2));
       if(net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {                   
          SumAmount();    
       }
    });";
        $script.=" function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
   $('.hold-amount').each(function() {
      var hold =  parseFloat($(this).val());
  if(hold != '' &&  !isNaN(hold)){
          total = total - hold;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }      ";

        $script.="$(document).ready(function(){
    $(document).on('click','.view-head',function(e){
    var id= $(this).attr('data-val');
  ViewBillHead(id);
    });
    function ViewBillHead(code){
        if(code != ''){         
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/payment/tbl-vsp-payment/bill-head']) . "',
                data: {'code' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#bill_head_view').html(data);
                   $('#BillHeadModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
});";
        $this->registerJs($script, View::POS_END, 'payment-adjust-script');
        ?>
        <?php
        $script = "$('.kv-panel-before').hide();";
        $this->registerJs($script, View::POS_END, 'panel-before-hide');
        ?>