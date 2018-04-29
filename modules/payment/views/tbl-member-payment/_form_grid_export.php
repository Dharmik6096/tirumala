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
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'dcs_payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
        return ['value' => $model['dcs_code']];
    }],
        ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name'],
        ['attribute' => 'total_amount', 'pageSummary' => true, 'value' => 'total_amount',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'total_deduction', 'pageSummary' => true, 'value' => 'total_deduction',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'final_amount', 'pageSummary' => true, 'value' => 'final_amount',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'member_count', 'pageSummary' => true, 'value' => 'member_count'],
    ];

    $grid_option = [
        'id' => 'member-payment-export-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'members' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Members'];
                return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members', 'cycle' => $model['dcs_payment_cycle_code'], 'dcs_code' => $model['dcs_code']], $options);
            },
                ]
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['create'], false);
            ?>
            <div class="clearfix"></div>
            <?php if (!empty($model->dcs_payment_cycle_code) && !empty($dataProvider->getModels())) { ?>
                <div class="col-md-12" >
                    <?= Html::button(Yii::t('app', 'Process Payment'), ['class' => 'btn btn-primary bank', 'name' => 'member']); ?>
                    <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'member-file']); ?>
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
                                url: '" . Url::to(['check-bank']) . "',
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
        $this->registerJs($script, View::POS_END, 'data-export-script');
        