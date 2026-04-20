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
                'options' => [
                    'class' => 'alignment_mapping'
                ],
    ]);
    ?>

    <div class="no-effect">
        <?php
        $attribute = [
                ['attribute' => 'event_code', 'label' => Yii::t('app', 'Event'), 'vAlign' => 'top', 'value' => function ($model) {
                    echo Html::activeHiddenInput($model, "[$model->event_code]union_code", ['value' => $model->union_code]);
                    echo Html::activeHiddenInput($model, "[$model->event_code]event_code_default", ['value' => $model->event_code_default]);
                    return $model->event_name;
                }, 'filter' => false],
                ['label' => Yii::t('app', 'Credit / Sub Ledger?'), 'vAlign' => 'top', 'format' => 'raw', 'value' => function ($model) use ($form) {
                    $out = "";
                    if ($model->ledger_credit == 1) {
                        $out .= Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-12', FALSE, false, "[$model->event_code]credit_ledger_code");
                    }
                    if ($model->sub_ledger_credit == 1) {
                        $out .= $form->field($model, "[$model->event_code]credit_sub_ledger", [
                                    'checkboxTemplate' => '<div class="mb0">{input} Credit Sub ledger ?</div>{error}{hint}'
                                ])->checkbox()->label(FALSE);
                    }
                    return ($out ?: '<span class="text-muted">N/A</span>');
                }
            ],
                ['label' => Yii::t('app', 'Debit / Sub Ledger?'), 'vAlign' => 'top', 'format' => 'raw', 'value' => function ($model) use ($form) {
                    $out = "";
                    if ($model->ledger_debit == 1) {
                        $out .= Yii::$app->dropdown->dropdown('ledger_mapping', $model, $form, 'col-sm-12', FALSE, false, "[$model->event_code]debit_ledger_code");
                    }
                    if ($model->sub_ledger_debit == 1) {
                        $out .= $form->field($model, "[$model->event_code]debit_sub_ledger", [
                                    'checkboxTemplate' => '<div class="mb0">{input} Debit Sub Ledger ?</div>{error}{hint}'
                                ])->checkbox()->label(FALSE);
                    }
                    return ($out ?: '<span class="text-muted">N/A</span>');
                }
            ],
                ['attribute' => 'voucher_type_code', 'label' => Yii::t('app', 'Voucher Type'), 'vAlign' => 'top', 'format' => 'raw', 'value' => function ($model) use ($form) {
                    return Yii::$app->dropdown->dropdown('voucher_types', $model, $form, 'col-sm-12', FALSE, false, "[$model->event_code]voucher_type_code");
                }
            ],
                ['label' => Yii::t('app', 'Voucher Narration'), 'vAlign' => 'top', 'format' => 'raw', 'value' => function ($model) use ($form) {
                    return $form->field($model, "[$model->event_code]voucher_narration")->textInput(['placeholder' => 'General Narration'])->label(false) .
                            $form->field($model, "[$model->event_code]voucher_txn_credit_narration")->textInput(['placeholder' => 'Credit Narration'])->label(false) .
                            $form->field($model, "[$model->event_code]voucher_txn_debit_narration")->textInput(['placeholder' => 'Debit Narration'])->label(false);
                }
            ],
                ['label' => Yii::t('app', 'Voucher Narration Local'), 'vAlign' => 'top', 'format' => 'raw', 'value' => function ($model) use ($form) {
                    return $form->field($model, "[$model->event_code]voucher_narration_local")->textInput(['placeholder' => 'Local Narration'])->label(false) .
                            $form->field($model, "[$model->event_code]voucher_txn_credit_narration_local")->textInput(['placeholder' => 'Credit Local'])->label(false) .
                            $form->field($model, "[$model->event_code]voucher_txn_debit_narration_local")->textInput(['placeholder' => 'Debit Local'])->label(false);
                }
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


