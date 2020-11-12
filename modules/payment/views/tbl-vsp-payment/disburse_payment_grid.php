<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['confirm-payment']);
$this->title = 'Process for Payment Disburse';
?>
<div class="no-effect" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'id' => 'vendor-payment-disburse',
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
        <?= Html::activeHiddenInput($model, 'customer_type'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
//        ['class' => 'kartik\grid\CheckboxColumn',
//            'rowSelectedClass' => GridView::TYPE_SUCCESS,
//            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
//            'checkboxOptions' => function($model) {
//        return ['value' => $model['dcs_code']];
//    }],
        //['attribute' => 'bmc_code', 'value' => 'dcsCode.bmcCode.bmc_name', 'label' => Yii::t('app', 'BMC')],
        //['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'label' => Yii::t('app', 'DCS')],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }, 'filter' => false],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type);
            }],
        ['attribute' => 'amount', 'pageSummary' => true, 'value' => 'amount',
            'label' => Yii::t('app', 'Milk Amount(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'addition', 'pageSummary' => true, 'value' => 'addition',
            'label' => Yii::t('app', 'Addition(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'deduction', 'pageSummary' => true, 'value' => 'deduction',
            'label' => Yii::t('app', 'Deduction(-)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'previous_hold', 'pageSummary' => true, 'value' => 'previous_hold',
            'label' => Yii::t('app', 'Previous Hold(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'previous_due', 'pageSummary' => true, 'value' => 'previous_due',
            'label' => Yii::t('app', 'Previous Due(-)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'hold_amount', 'pageSummary' => true, 'value' => 'hold_amount',
            'label' => Yii::t('app', 'Hold Amount(-)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'adjust_amount', 'pageSummary' => true, 'value' => 'adjust_amount',
            'label' => Yii::t('app', 'Additional Pay(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'final_pay', 'pageSummary' => true, 'value' => 'final_pay',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
    ];

    $grid_option = [
        'id' => 'vsp-payment-export-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
    ];

    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['create'], false);
    ?>
    <div class="clearfix"></div>
    <?php if (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) { ?>
        <div class="col-md-12" >
            <?= Html::button(Yii::t('app', 'Process Payment'), ['class' => 'btn btn-primary bank', 'name' => 'vsp']); ?>
            <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'vsp-file']); ?>
        </div>
    <?php } ?>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = "
        $('.kv-panel-before').hide();
        $('.sub').on('click',function(){
        $('#flag').val($(this).prop('name'));
        $('form#vendor-payment-disburse').submit();
    });
    
    $('.bank').on('click',function(){
    $('#error-summary').hide();
        $('#flag').val($(this).prop('name'));
        $('form#vendor-payment-disburse').submit();
       /*  $.ajax({
                                type: 'post',
                                url: '" . Url::to(['tbl-member-payment/check-bank']) . "',
                                data: 'union_code=" . $model->union_code . "',
                                success: function (data) {
                                    var obj = $.parseJSON(data);
                                    if (obj.status == 'success')
                                    {
                                      $('form#vendor-payment-disburse').submit();
                                    }else{
                                       bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                                    }
                                }
                            }); */

    });
    ";
$this->registerJs($script, View::POS_END, 'vsp-payment-script');
