<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['confirm-payment']);
$this->title = 'Process for Payment Disburse';
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'transporter_payment_cycle'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
        return ['value' => $model['transporter_payment_code']];
    }],
        ['attribute' => 'transporter_code', 'value' => 'transporterCode.transporter_name', 'label' => Yii::t('app', 'Transporter')],
        ['attribute' => 'bmc_code', 'value' => 'bmcCode.bmc_name', 'label' => Yii::t('app', 'BMC')],
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
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'total_vehicle', 'pageSummary' => true, 'value' => 'total_vehicle'],
    ];

    $grid_option = [
        'id' => 'tp-payment-export-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'vehicles' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Vehicles'];
                return GhostHtml::a('<i class="fa fa-truck"></i>', ['/payment/tbl-transporter-payment/payment-vehicles', 'transporter_payment_code' => $model['transporter_payment_code']], $options);
            },
                ]
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['create'], false);
            ?>
            <div class="clearfix"></div>
            <?php if (!empty($model->transporter_payment_cycle) && !empty($dataProvider->getModels())) { ?>
                <div class="col-md-12" >
                    <?= Html::button(Yii::t('app', 'Process Payment'), ['class' => 'btn btn-primary bank', 'name' => 'tp']); ?>
                    <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'tp-file']); ?>
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
        $('form#w1').submit();
    });
    
    $('.bank').on('click',function(){
    $('#error-summary').hide();
        $('#flag').val($(this).prop('name'));
         $.ajax({
                                type: 'post',
                                url: '" . Url::to(['tbl-member-payment/check-bank']) . "',
                                data: 'union_code=" . $model->union_code . "',
                                success: function (data) {
                                    var obj = $.parseJSON(data);
                                    if (obj.status == 'success')
                                    {
                                      $('form#w1').submit();
                                    }else{
                                       bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                                    }
                                }
                            });

    });
    ";
        $this->registerJs($script, View::POS_END, 'transportar-payment-script');
        