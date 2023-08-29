<?php

use yii\helpers\Url;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = !empty($title) ? $title : Yii::t('app', 'Member Detail');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?php
        $attribute = [
                ['attribute' => 'customer_type', 'value' => function($model) {
                    return (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'));
                }],
                ['attribute' => 'customer_code'],
                ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true);
                }],
                ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, true);
                }],
                ['attribute' => 'customer_name'],
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
            'id' => 'member-bonus-list-index',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'member-bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-bonus_payment_code' => $model->bonus_payment_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-bonus-payment/bill-head', 'bonus_payment_code' => $model->bonus_payment_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], false);
        ?>
        <div class="clearfix"></div>
    </div>
</div>
<div id='bill_head_view'></div>
<?php
$script = " 


$(document).on('click','.view-head',function(e){
    var bonus_payment_code= $(this).attr('data-bonus_payment_code');
    ViewBillHead(bonus_payment_code);
});

function ViewBillHead(bonus_payment_code){
    if(bonus_payment_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-bonus-payment/bill-head']) . "',
            data: {'bonus_payment_code' : bonus_payment_code},
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


$this->registerJs($script, View::POS_END, 'bonus-payment-head-script');
?>