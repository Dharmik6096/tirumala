<?php

use yii\helpers\Url;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = !empty($title) ? $title : Yii::t('app', 'Payment Detail');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?php
        $attribute = [
                ['attribute' => 'party_master_code'],
                ['attribute' => 'kg_fat'],
                ['attribute' => 'kg_snf'],
                ['attribute' => 'qty'],
                ['attribute' => 'amount'],
                ['attribute' => 'addition'],
                ['attribute' => 'deduction'],
                ['attribute' => 'net_payable'],
                ['attribute' => 'bank_name'],
                ['attribute' => 'branch_name'],
                ['attribute' => 'ifsc'],
                ['attribute' => 'bank_account_no'],
                ['attribute' => 'beneficiary_name'],
        ];

        $grid_option = [
            'id' => 'member-party-list-index',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'default_sorting' => FALSE,
            'actions' => [
                'member-bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-party_payment_code' => $model->party_payment_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-party-payment/bill-head', 'party_payment_code' => $model->party_payment_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
        ?>
        <div class="clearfix"></div>
    </div>
</div>
<div id='bill_head_view'></div>
<?php
$script = " 


$(document).on('click','.view-head',function(e){
    var party_payment_code= $(this).attr('data-party_payment_code');
    ViewBillHead(party_payment_code);
});

function ViewBillHead(party_payment_code){
    if(party_payment_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-party-payment/bill-head']) . "',
            data: {'party_payment_code' : party_payment_code},
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
}";


$this->registerJs($script, View::POS_END, 'party-payment-head-script');
?>