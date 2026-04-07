<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$action = Url::to(['confirm-payment']);
$this->title = 'Process for Payment Hold Release Disburse';
?>
<div class="no-effect" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'id' => 'vendor-payment-hold-release',
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
         <?php if (is_array($model->mcc_plant_code)) { ?>
            <?php foreach ($model->mcc_plant_code as $mcc_plant_code) { ?>
                <?= Html::activeHiddenInput($model, 'mcc_plant_code[]', ['value' => $mcc_plant_code]); ?>
            <?php } ?>
        <?php } else { ?>
            <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
        <?php } ?>
        <?php if (is_array($model->bmc_code)) { ?>
            <?php foreach ($model->bmc_code as $bmc_code) { ?>
                <?= Html::activeHiddenInput($model, 'bmc_code[]', ['value' => $bmc_code]); ?>
            <?php } ?>
        <?php } else { ?>
            <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
        <?php } ?>
        <?= Html::activeHiddenInput($model, 'customer_type'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php

    $attribute = [
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }, 'filter' => false],
             ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                },],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return !empty($model->customer_name) ? $model->customer_name : Yii::$app->general->getCustomer($model, $model->customer_type);
            }],
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
        //     ['attribute' => 'adjust_recovery', 'pageSummary' => true,
        //     'hAlign' => Yii::$app->general->ColoumnAlign(),
        //     'format' => Yii::$app->general->CurrencyFormat()
        // ],
        //     ['attribute' => 'recovery', 'pageSummary' => true,
        //     'hAlign' => Yii::$app->general->ColoumnAlign(),
        //     'format' => Yii::$app->general->CurrencyFormat()
        // ],
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
    <?php if (!empty($dataProvider->getModels())) { ?>
        <div class="col-md-12" >              
            <?= Html::button(Yii::t('app', 'Process Payment'), ['class' => 'btn btn-primary bank', 'name' => 'vendor']); ?>
            <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'vendor-file']); ?>
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
    $('form#vendor-payment-hold-release').submit();
});

$('.bank').on('click',function(){
    $('#error-summary').hide();
    $('#flag').val($(this).prop('name'));
    $('form#vendor-payment-hold-release').submit();
});
";
$this->registerJs($script, View::POS_END, 'vsp-payment-script');
