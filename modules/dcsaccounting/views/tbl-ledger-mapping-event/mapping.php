<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Ledger Mapping Event');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'ledger-mapping-event-form',
                'enableAjaxValidation' => false,
    ]);
    ?>

    <div class="no-effect table_form">
        <?php
        $attribute = [
                ['attribute' => 'event_code', 'label' => Yii::t('app', 'Event'), 'value' => function($model) {
                    echo Html::activeHiddenInput($model, "[$model->event_code]union_code", ['value' => $model->union_code]);
                    echo Html::activeHiddenInput($model, "[$model->event_code]event_code_default", ['value' => $model->event_code_default]);
                    return $model->event_name;
                }, 'filter' => false],
                ['attribute' => 'credit_ledger_code', 'label' => Yii::t('app', ' Credit Ledger'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    if ($model->ledger_credit == 1) {
                        return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "[$model->event_code]credit_ledger_code");
                    }
                    return '<span class="text-muted">N/A</span>';
                }, 'filter' => false
            ],
                ['attribute' => 'credit_sub_ledger', 'label' => Yii::t('app', 'Credit Sub Ledger?'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    if ($model->sub_ledger_credit == 1) {
                        return $form->field($model, "[$model->event_code]credit_sub_ledger", [
                                    'checkboxTemplate' => '<div class="mb0 center_text">{input}</div>{error}{hint}'
                                ])->checkbox()->label(FALSE);
                    }
                    return '<span class="text-muted">N/A</span>';
                }, 'filter' => false
            ],
                ['attribute' => 'debit_ledger_code', 'label' => Yii::t('app', ' Debit Ledger'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    if ($model->ledger_debit == 1) {
                        return Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-3', FALSE, false, "[$model->event_code]debit_ledger_code");
                    }
                    return '<span class="text-muted">N/A</span>';
                }, 'filter' => false
            ],
                ['attribute' => 'debit_sub_ledger', 'label' => Yii::t('app', 'Debit Sub Ledger?'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    if ($model->sub_ledger_debit == 1) {
                        return $form->field($model, "[$model->event_code]debit_sub_ledger", [
                                    'checkboxTemplate' => '<div class="mb0 center_text">{input}</div>{error}{hint}'
                                ])->checkbox()->label(FALSE);
                    }
                    return '<span class="text-muted">N/A</span>';
                }, 'filter' => false
            ],
                ['attribute' => 'voucher_type_code', 'label' => Yii::t('app', 'voucher Type'),
                'format' => 'raw',
                'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdown('voucher_types', $model, $form, 'col-sm-3', FALSE, false, "[$model->event_code]voucher_type_code");
                },
                'filter' => false
            ],
        ];

        $grid_option = [
            'id' => 'ledger-mapping-event-grid',
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
            <?php }
            ?>
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
        $('#ledger-mapping-event-form').submit();
    });
";
$this->registerJs($script, View::POS_END, 'ledger-mapping-event-script');
?>


