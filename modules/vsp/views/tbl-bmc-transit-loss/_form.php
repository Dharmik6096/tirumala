<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$action = Url::to(['update-transit-loss']);
?>
<div class="no-effect" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'transit-loss-update',
                'action' => $action,
    ]);
    ?>

    <?php
    $attribute = [
        ['attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code');
            }, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        [
            'attribute' => 'date_time_of_collection',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }],
        ['attribute' => 'from_shift', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => false],
        ['attribute' => 'kg_fat'],
        ['attribute' => 'kg_snf'],
        ['attribute' => 'qty'],
        ['attribute' => 'amount', 'pageSummary' => true, 'value' => 'amount',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'loss_amount', 'pageSummary' => true, 'value' => 'loss_amount',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
        ['attribute' => 'loss_applied_to',
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'table_radio text-center div_margin_0'];
            },
            'value' => function ($model, $key, $index) use ($form) {
                return Html::activeHiddenInput($model, 'transit_loss_code[' . $index . ']', ['value' => $model->transit_loss_code]) . $form->field($model, 'loss_applied_to[' . $index . ']')->inline()->radioList([1 => 'VSP', 2 => 'TPT'], ['value' => $model->loss_applied_to])->label(false);
            },
        ],
    ];

    $grid_option = [
        'id' => 'transit-loss-update-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
    ];

    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
    ?>
</div>
<div class="col-sm-2 mt10" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "$('.kv-panel-before').hide();";
$script .= '$("#adjust").click(function() {
   $("#transit-loss-update").submit();
});
';
$this->registerJs($script, View::POS_END, 'transit-loss-update-grid-script');
