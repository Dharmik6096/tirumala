<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$this->title = 'Transporter Payment Process : Step 2';
if ($transporter_type == 0) {
    $bmc_info = Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code') . ' > ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . ' > ' .
            Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
    if (is_array($model->bmc_code) && count($model->bmc_code) > 1) {
        $bmc_info = 'All > ' . Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
    }
} else {
    $bmc_info = $model->vendor_code . ' > ' . $model->transporter_name . ' > ' .
            Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
}

$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
$net_amt = array_sum(array_map(function($array) {
            return $array['net_amount'];
        }, $array));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body hide_help_block">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>  
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust-primary',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        $attribute = [
            ['attribute' => 'route_code', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->routeCode, 'ref_code');
                }, 'label' => Yii::t('app', 'Route Code'), 'visible' => ($transporter_type == 0)],
            ['attribute' => 'route_code', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
                }, 'visible' => ($transporter_type == 0)
            ],
            ['attribute' => 'transporter_name', 'visible' => ($transporter_type == 0)],
            ['attribute' => 'parsing_no'],
            ['attribute' => 'fixed_amount', 'pageSummary' => true, 'visible' => ($transporter_type == 0)],
            ['attribute' => 'total_amount', 'pageSummary' => true],
            ['attribute' => 'total_addition', 'pageSummary' => true],
            ['attribute' => 'total_deduction', 'pageSummary' => true],
            ['attribute' => 'net_amount',
                'pageSummary' => true,
                'contentOptions' => ['class' => 'net-amount'],
            ],
            ['attribute' => 'tds_amount', 'pageSummary' => true],
            ['attribute' => 'adjust_amount',
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, '[' . $index . ']transporter_payment_code', ['value' => $model->transporter_payment_code]) . $form->field($model, '[' . $index . ']adjust_amount')->textInput(['value' => $model->adjust_amount, 'class' => 'number-validate-negative adjust-amount cal-amount form-control',])->label(FALSE);
                },
            ],
            ['attribute' => 'final_amount',
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $index . ']final_amount')->textInput(['class' => 'number-validate final-amount form-control', "disabled" => TRUE, 'value' => $model->final_amount])->label(FALSE);
                },
            ],
            ['attribute' => 'adjust_remark',
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $index . ']adjust_remark')->textInput(['value' => $model->adjust_remark])->label(FALSE);
                },
            ],
        ];
        ?>
        <div class="hide-grid-settings">
            <?php
            $grid_option = [
                'id' => 'tpt-payment-adjust-primary-grid',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => true,
                'actions' => [
                    'payment-detail-primary' => function ($url, $model) {
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-payment-detail', 'title' => 'View Payment Detail', 'data-val' => $model->transporter_payment_code];
                        return GhostHtml::a_alert('<i class="fa fa fa-money-bill"></i>', ['/payment/tbl-transporter-payment/payment-detail-primary', 'id' => $model->transporter_payment_code], $options);
                    },
                ]
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
            ?>
        </div>
    </div>
    <div class="panel-footer" >

        <?php
        if (!empty($dataProvider->getModels())) {
            echo Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust']);
        }
        ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
    </div>
</div>
<?php ActiveForm::end(); ?>
<div id='payment_detai_view'></div>
<?php
$script = '$("#adjust").click(function() {
   $("#payment-adjust-primary").submit();
});
';
$script .= " $('.cal-amount').on('blur',function(){     
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var net = parseFloat(parent.find('.net-amount').text());
        parent.find('.final-amount').val('');
        if(adjust == '' ||  isNaN(adjust)){
        adjust=0;
        }       
        var final = net + adjust;  
        if(final != '' && final < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Final Payable should not be less than net amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(final != '' &&  !isNaN(final)){
         parent.find('.final-amount').val(final.toFixed(2));
          SumAmount();
        }
       }
    });";
$script .= " function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
        total=$net_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }      ";

$script .= "$(document).ready(function(){
    $(document).on('click','.view-payment-detail',function(e){
    var id= $(this).attr('data-val');
  ViewPaymentDetail(id);
    });
    function ViewPaymentDetail(code){
        if(code != ''){         
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/payment/tbl-transporter-payment/payment-detail-primary']) . "',
                data: {'code' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#payment_detai_view').html(data);
                   $('#PaymentDetailModal').modal('toggle');              
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
       