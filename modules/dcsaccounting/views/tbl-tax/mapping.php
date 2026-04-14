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
                ['attribute' => 'tax_detail_code', 'label' => Yii::t('app', 'Tax Details'), 'value' => function($model) {
                    return $model->basic_tax_name . ' (' . $model->percentage . '%)';
                }, 'filter' => false],
                ['attribute' => 'ledger_code', 'label' => Yii::t('app', 'Ledger'), 'format' => 'raw', 'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "[$model->tax_detail_code]ledger_code");
                }, 'filter' => false
            ],
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
