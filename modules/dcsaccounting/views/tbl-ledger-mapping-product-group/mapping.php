<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'Ledger Mapping Product Group');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <?php
    $form = ActiveForm::begin([
        'id' => 'product-group-mapping-form',
        'enableAjaxValidation' => false,
    ]);
    ?>

    <div class="no-effect table_form">
        <?php
        $attribute = [
            ['attribute' => 'product_group_code', 'label' => Yii::t('app', 'Product Group'), 'value' => function ($model) {
                echo Html::activeHiddenInput($model, "tblLedgerMappingProductGroup[$model->product_group_code][union_code]", ['value' => $model->union_code]);
                return $model->product_group_name;
            }],
            [
                'attribute' => 'ledger_sale_code',
                'label' => Yii::t('app', 'Ledger (Sale)'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {

                    return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "tblLedgerMappingProductGroup[$model->product_group_code][ledger_sale_code]");
                },
                'filter' => false
            ],
            [
                'attribute' => 'ledger_purchase_code',
                'label' => Yii::t('app', 'Ledger (Purchase)'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "tblLedgerMappingProductGroup[$model->product_group_code][ledger_purchase_code]");
                },
                'filter' => false
            ],
        ];

        $grid_option = [
            'id' => 'product-group-mapping-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
        ?>
    </div>

    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('SAVE', $model, 'save'); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = "
    $('.kv-panel-before').hide();
      $('.save').click(function(e) {
        e.preventDefault();
        $('#product-group-mapping-form').submit();
    });
";
$this->registerJs($script, View::POS_END, 'product-group-mapping-script');
?>