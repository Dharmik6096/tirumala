<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use kartik\grid\GridView;
use yii\web\View;

$this->title = Yii::t('app', 'Member Bill Head Mapping');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'bill-head-mapping-form',
                'enableAjaxValidation' => false,
    ]);
    ?>

    <div class="no-effect table_form">
        <?php
        $attribute = [
                ['attribute' => 'bill_head_code', 'label' => Yii::t('app', 'Member Bill Head'), 'value' => function($model) {
                    echo Html::activeHiddenInput($model, "[$model->bill_head_code]union_code", ['value' => $model->union_code]);
                    return $model->bill_head_name;
                }, 'filter' => false],
                ['attribute' => 'ledger_code', 'label' => Yii::t('app', 'Ledger'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "[$model->bill_head_code]ledger_code");
                }, 'filter' => false
            ],
                ['attribute' => 'has_sub_ledger', 'label' => Yii::t('app', 'Has Sub Ledger?'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    return $form->field($model, "[$model->bill_head_code]has_sub_ledger", [
                                'checkboxTemplate' => '<div class="mb0 center_text">{input}</div>{error}{hint}'
                            ])->checkbox()->label(FALSE);
                }, 'filter' => false
            ],
                ['attribute' => 'credit_debit', 'label' => Yii::t('app', 'Credit/Debit'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdownStatic('credit_debit', $model, $form, '', false, false, "[$model->bill_head_code]credit_debit", false, false, true, true);
                },
                'filter' => false
            ],
        ];

        $grid_option = [
            'id' => 'bill-head-mapping-grid',
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
        $('#bill-head-mapping-form').submit();
    });
";
$this->registerJs($script, View::POS_END, 'bill-head-mapping-script');
?>


