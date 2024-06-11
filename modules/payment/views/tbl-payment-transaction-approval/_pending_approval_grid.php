<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

// $action = Url::to(['confirm-payment']);
$form = ActiveForm::begin([
    'id' => 'payment-transaction-approval',
]);
$attributes = [
    // ['attribute' => 'union_code', 'value' => function($model, $key, $index) use ($form) {
    //     echo Html::activeHiddenInput($model, '[' . $index . ']payment_transaction_approval_code', ['value' => $model->payment_transaction_approval_code]);
    //     $cnt = $index+1;
    //     echo Html::activeHiddenInput($model, '[' . $cnt . ']payment_transaction_approval_code', ['value' => $model->payment_transaction_approval_code]);
    //     return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    // }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    // ['attribute' => 'plant_code', 'value' => function($model) {
    //     return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    // }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    // ['attribute' => 'mcc_plant_code', 'value' => function($model) {
    //     return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
    // }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
    'label' => Yii::t('app', 'BMC Code'),
    'value' => function($model, $key, $index) use ($form){
        echo Html::activeHiddenInput($model, '[' . $index . ']process_approval_code', ['value' => $model->process_approval_code]);
        echo Html::activeHiddenInput($model, '[' . $index . ']payment_transaction_approval_code', ['value' => $model->payment_transaction_approval_code]);
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
    ['attribute' => 'customer_type','filter' => false],
    ['attribute' => 'approval_status','filter' => false],
    // ['attribute' => 'remarks',],
];

$grid_option = [
    'id' => 'payment-transaction-export-grid',
    'attributes' => $attributes,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
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
    $('#payment-transaction-approval').submit();
});
";
$this->registerJs($script, View::POS_END, 'payment-transaction-approval');
?>

