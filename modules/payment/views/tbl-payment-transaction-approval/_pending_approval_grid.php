<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$form = ActiveForm::begin([
    'id' => 'payment-transaction-approval',
]);
?>
<div class="no-effect table_form" >
<?php
$attributes = [
    ['class' => 'kartik\grid\CheckboxColumn',
        'rowSelectedClass' => GridView::TYPE_SUCCESS,
        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
        'checkboxOptions' => function ($model, $key, $index) {
            if($index == 0){
                echo Html::hiddenInput('remarks', null, ['id' => 'remarks']);
            }
            return ['value' => $model['process_approval_code']];
        }],
    ['attribute' => 'bmc_code',
    'value' => function($model){
        return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
    },
    'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'payment_cycle', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => false, 'format' => 'raw'],
    ['attribute' => 'customer_type','filter' => false],
    [
        'attribute' => 'payment_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->payment_date);
        },'filter' => false],
    ['attribute' => 'kg_fat','filter' => false],
    ['attribute' => 'kg_snf','filter' => false],
    ['attribute' => 'qty','filter' => false],
    ['attribute' => 'avg_fat','filter' => false],
    ['attribute' => 'avg_snf','filter' => false],
    ['attribute' => 'avg_rate','filter' => false],
    ['attribute' => 'total_amount','filter' => false],
    ['attribute' => 'total_deduction','filter' => false],
    ['attribute' => 'final_amount','filter' => false],
    ['attribute' => 'total_count','filter' => false],
    ['attribute' => 'approval_status','filter' => false],
    ['attribute' => 'remarks','filter' => false],
];

$grid_option = [
    'id' => 'payment-transaction-export-grid',
    'attributes' => $attributes,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'detail-view' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View'), 'class' => '', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/payment/tbl-payment-transaction-approval/view', 'payment_transaction_approval_code' => $model->payment_transaction_approval_code, 'bmc_code' => $model->bmc_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
?>
</div>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo '<div class = "form-group">';
        echo Html::label('Remarks', 'remarks', ['class' => 'control-label']);
        echo Html::textArea('add_remarks','', ['class'=>'form-control remark-text-aria', 'id' => 'add_remarks']);
        echo '</div>';
        echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary', 'id' => 'approve']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end();
$script = "
$('.kv-panel-before').hide();
$('#approve').click(function(e) {
    e.preventDefault();
    var checkBoxCount = $('.kv-row-checkbox:checked').length;
    if(checkBoxCount > 0) {
        var remarks = $('#add_remarks').val();
        $('#remarks').val(remarks);
        bootbox.confirm({
            message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>Aye you sure payment transaction approve?</span>',
            buttons: {
                confirm: {
                    label: '" . Yii::t('app', 'Yes') . " ',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '" . Yii::t('app', 'No') . "' ,
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    $('#payment-transaction-approval').submit();
                }
            }
        });
    } else {
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
    }
});
";
$this->registerJs($script, View::POS_END, 'payment-transaction-approval');
?>

