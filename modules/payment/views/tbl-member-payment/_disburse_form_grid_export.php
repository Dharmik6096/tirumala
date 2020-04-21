<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['disburse-member-payment']);
?>
<div class="" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'plant_code'); ?>
        <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
        <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
//            ['class' => 'kartik\grid\CheckboxColumn',
//            'rowSelectedClass' => GridView::TYPE_SUCCESS,
//            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
//            'checkboxOptions' => function($model) {
//                return ['value' => $model['dcs_code']];
//            }],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }],
            ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }],
            ['attribute' => 'member_count'],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'qty', 'pageSummary' => true],
            ['attribute' => 'total_amount', 'value' => 'total_amount',
            'pageSummary' => true
        ],
            ['attribute' => 'addition', 'value' => 'addition',
            'pageSummary' => true
        ],
            ['attribute' => 'total_deduction', 'value' => 'total_deduction',
            'pageSummary' => true
        ],
            ['attribute' => 'previous_hold', 'pageSummary' => true
        ],
            ['attribute' => 'previous_due', 'pageSummary' => true
        ],
            ['attribute' => 'final_amount',
            'pageSummary' => true,
            'value' => function ($model) {
                $addition = !empty($model->addition) ? $model->addition : 0;
                $deduction = !empty($model->total_deduction) ? $model->total_deduction : 0;
                return $model->net_payable + $addition - $deduction;
            },
            'contentOptions' => ['class' => 'final-amount'],
        ],
    ];

    $grid_option = [
        'id' => 'member-payment-export-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'members' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'target' => '_blank', 'data-placement' => 'top', 'data-original-title' => 'View Members'];
                return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members-list', 'cycle' => $model['payment_cycle_code'], 'dcs_code' => $model['dcs_code']], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false);
    ?>
    <div class="clearfix"></div>
    <?php if (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) { ?>
        <div class="col-md-12 mt10" >
            <?= Html::button(Yii::t('app', 'Disburse Payment'), ['class' => 'btn btn-primary sub', 'name' => 'member']); ?>
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
//        var checkBoxCount = $('.kv-row-checkbox:checked').length;
//        if(checkBoxCount > 0) {
//            $('#flag').val($(this).prop('name'));
//            $('form#w1').submit();
//        } else {
//            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
//        }
        $('#flag').val($(this).prop('name'));
        $('form#w1').submit();
    });
    
    $('.bank').on('click',function(){
        $('#error-summary').hide();
        $('#flag').val($(this).prop('name'));
        $('form#w1').submit();
//         $.ajax({
//            type: 'post',
//            url: '" . Url::to(['check-bank']) . "',
//            data: 'union_code=" . $model->union_code . "',
//            success: function (data) {
//                var obj = $.parseJSON(data);
//                if (obj.status == 'success')
//                {
//                  $('form#w1').submit();
//                }else{
//                   bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
//                }
//            }
//        });

    });
    ";
$this->registerJs($script, View::POS_END, 'data-export-script');
