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
                ['attribute' => 'product_group_code', 'label' => Yii::t('app', 'Product Group'), 'format' => 'raw', 'value' => function ($model) {
                    echo Html::hiddenInput("tblLedgerMappingProductGroup[$model->product_group_code][union_code]", $model->union_code, ['id' => 'union_code_' . $model->product_group_code]);
                    return $model->product_group_name;
                }],
                [
                'attribute' => 'ledger_sale_code',
                'label' => Yii::t('app', 'Ledger (Sale)'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    echo Html::hiddenInput("ledger_type_sale[$model->product_group_code]", 'sale', ['id' => 'ledger_type_sale_' . $model->product_group_code]);
                    return Yii::$app->dropdown->ledgerList($model, $form, 'union_code_' . $model->product_group_code . ',ledger_type_sale_' . $model->product_group_code, 'ledger_sale_code', false, false, false, true, "tblLedgerMappingProductGroup[$model->product_group_code][ledger_sale_code]");
                },
                'filter' => false
            ],
                [
                'attribute' => 'ledger_purchase_code',
                'label' => Yii::t('app', 'Ledger (Purchase)'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    echo Html::hiddenInput("ledger_type_purchase[$model->product_group_code]", 'purchase', ['id' => 'ledger_type_purchase_' . $model->product_group_code]);
                    return Yii::$app->dropdown->ledgerList($model, $form, 'union_code_' . $model->product_group_code . ',ledger_type_purchase_' . $model->product_group_code, 'ledger_purchase_code', false, false, false, true, "tblLedgerMappingProductGroup[$model->product_group_code][ledger_purchase_code]");
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