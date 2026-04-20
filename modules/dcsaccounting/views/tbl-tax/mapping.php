<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Tax Details Ledger Mapping');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'tax-detail-mapping-form',
                'enableAjaxValidation' => false,
    ]);
    ?>

    <div class="no-effect table_form">
        <?php
        $attribute = [
                ['attribute' => 'tax_code', 'label' => Yii::t('app', 'Tax'), 'value' => function($model) {
                    echo Html::activeHiddenInput($model, "[$model->tax_detail_code]union_code", ['value' => $model->union_code, 'id' => "union_code_$model->tax_detail_code"]);
                    return $model->tax_name;
                }, 'filter' => false],
                ['attribute' => 'tax_detail_code', 'label' => Yii::t('app', 'Tax Details'), 'format' => 'raw', 'value' => function($model) {
                    $hiddenName = Html::hiddenInput("TblTaxDetail[$model->tax_detail_code][basic_tax_name]", $model->basic_tax_name);
                    return $hiddenName . $model->basic_tax_name . ' (' . $model->percentage . '%)';
                }, 'filter' => false],
                ['attribute' => 'purchase_ledger_code', 'label' => Yii::t('app', 'Purchase Ledger'), 'format' => 'raw', 'value' => function ($model) use ($form) {
                    $hidden = Html::hiddenInput("TblTaxDetail[$model->tax_detail_code][ledger_type_purchase]", 'purchase', ['id' => 'ledger_type_purchase_' . $model->tax_detail_code]);
                    return $hidden . Yii::$app->dropdown->ledgerList($model, $form, "union_code_$model->tax_detail_code,ledger_type_purchase_$model->tax_detail_code", "purchase_ledger_code", false, false, false, true, "[$model->tax_detail_code]purchase_ledger_code");
                }, 'filter' => false],
                ['attribute' => 'sale_ledger_code', 'label' => Yii::t('app', 'Sale Ledger'), 'format' => 'raw', 'value' => function ($model) use ($form) {
                    $hidden = Html::hiddenInput("TblTaxDetail[$model->tax_detail_code][ledger_type_sale]", 'sale', ['id' => 'ledger_type_sale_' . $model->tax_detail_code]);
                    return $hidden . Yii::$app->dropdown->ledgerList($model, $form, "union_code_$model->tax_detail_code,ledger_type_sale_$model->tax_detail_code", "sale_ledger_code", false, false, false, true, "[$model->tax_detail_code]sale_ledger_code");
                }, 'filter' => false],
        ];

        $grid_option = [
            'id' => 'tax-detail-mapping-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
        ?>
    </div>

    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php if (!empty($dataProvider->getModels())) { ?>
                <?= Yii::$app->controls->save('SAVE', $model, 'save'); ?>
            <?php } ?>
            <?= Html::a('cancel', Url::to(['index']), ['class' => 'btn btn-danger apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = "
    $('.kv-panel-before').hide();
      $('.save').click(function(e) {
        e.preventDefault();
        $('#tax-detail-mapping-form').submit();
    });
";
$this->registerJs($script, View::POS_END, 'tax-detail-mapping-script');
?>