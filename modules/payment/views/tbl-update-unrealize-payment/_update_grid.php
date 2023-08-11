<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;
use app\modules\payment\models\TblPaymentHoldReason;

$form = ActiveForm::begin([
    'id' => 'update-unrelease-payment',
    'action' => Url::to(['update-unrelease-payment']),
]);
echo Html::hiddenInput('payment_type', $searchParameter->payment_type);
?>

<div class=" no-effect table_form">
    
    <?php
    $attribute = [

        [
            'class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function ($model) use ($searchParameter) {
                $values = $searchParameter->payment_type == 'VENDOR' ? $model->vsp_payment_code : $model->payment_sumary_code;
                return ['class' => 'checkbox-collection', 'value' => $values];
            }
        ],
        [
            'attribute' => 'dcs_code',
            'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false
        ],
        ['attribute' => 'member_count', 'filter' => FALSE, 'visible' => ($searchParameter->payment_type == 'MEMBER') ? true : false],
        [
            'attribute' => $searchParameter->payment_type == 'VENDOR' ? 'final_pay' : 'final_amount',
            'filter' => FALSE,
        ],
        ['attribute' => 'disburse_amount', 'filter' => FALSE],
        [
            'attribute' => 'payment_cycle_code',
            'value' => function ($model) {
                $fromDatetime = Yii::$app->controls->view_date($model->from_datetime);
                $toDatetime = Yii::$app->controls->view_date($model->to_datetime);
                return "{$fromDatetime} To {$toDatetime}";
            },
            'filter' => FALSE,
        ],
        [
            'attribute' => 'disburse_date',
            'value' => function ($model) {
                return Yii::$app->controls->view_datetime($model->disburse_date);
            }, 'filter' => FALSE
        ],
        [
            'attribute' => 'hold_reason',
            'format' => 'raw',
            'value' => function ($model, $index) use ($form, $searchParameter) {
                $tblId = $searchParameter->payment_type == 'VENDOR' ? $model->vsp_payment_code : $model->payment_sumary_code;
                return '<span class=\'hold_reason\'>' . Yii::$app->dropdown->dropdown('hold_reason', $model, $form, '', FALSE, FALSE, '[' .  $tblId . ']hold_reason', FALSE, TRUE, $model->hold_reason) . '</span>';
            },
        ],
        [
            'attribute' => 'release_date',
            'format' => 'raw',
            'value' => function ($model, $index) use ($form, $searchParameter) {
                $tblId = $searchParameter->payment_type == 'VENDOR' ? $model->vsp_payment_code : $model->payment_sumary_code;
                return '<span class=\'date_change\'>' . $form->field($model, '[' . $tblId . ']release_date')->textInput(['value' => $model->release_date, 'class' => 'form-control',])->label(FALSE) . '</span>';
            },
            // 'filterType' => GridView::FILTER_DATE,
            // 'filterWidgetOptions' => [
            //     'pluginOptions' => [
            //         'format' => 'dd-mm-yyyy',
            //         'autoclose' => true
            //     ]
            // ],
            // 'value' => function ($model) use ($form) {
            //     return Yii::$app->controls->date($model, $form, 'release_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, $model->disburse_date, false, false);
            // }
        ],
    ];

    $grid_option = [
        'id' => 'update-unrelease-payment-list-data',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer">
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'id' => 'update-selected']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
$('.kv-panel-before').hide();
$('.searchBtn').hide();
$('#update-selected').click(function(e) {
    e.preventDefault();
    
    var checkBoxCount = $('.kv-row-checkbox:checked').length;
    if (checkBoxCount > 0) {
        var isValid = true;
        $('.checkbox-collection:checked').each(function() {
            var rowId = $(this).val();
            var holdReason = $('#tblmemberpaymentsummary-' + rowId + '-hold_reason').val();
            var releaseDate = $('#tblmemberpaymentsummary-' + rowId + '-release_date').val();
            
            // Apply validation only for selected rows
            if (!holdReason && !releaseDate) {
                isValid = false;
                $('#tblmemberpaymentsummary-' + rowId + '-hold_reason').addClass('has-error');
                $('#tblmemberpaymentsummary-' + rowId + '-release_date').addClass('has-error');
            } else {
                $('#tblmemberpaymentsummary-' + rowId + '-hold_reason').removeClass('has-error');
                $('#tblmemberpaymentsummary-' + rowId + '-release_date').removeClass('has-error');
            }
        });

        if (isValid) {
            $('#flag').val($(this).prop('name'));
            var paymentType = $('[name=\"payment_type\"]').val();
            $('#update-unrelease-payment').append('<input type=\"hidden\" name=\"payment_type\" value=\"' + paymentType + '\"/>'); // Add hidden input
            $('#update-unrelease-payment').submit();
        } else {
            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Hold Reason and Release Date are required for selected records.') . "</span>');
        }
    } else {
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select at least one Record') . "</span>');
    }
});
";
$this->registerJs($script, View::POS_END, 'update-unrelease-payment');

