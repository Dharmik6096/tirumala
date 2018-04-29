<?php
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Sale installments'));
//$this->params['menu'][]=Yii::$app->controls->add('Sale installments');
?>

<div class="tbl-product-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
<?php

$attribute = [
    //'product_sale_code',
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter'=>false],
    //['attribute' => 'union_code', 'value' => 'unionCode.union_name','filter'=>false],
    ['attribute' => 'member_code', 'value' => 'memberCode.member_name',  'filter'=>false],
    ['attribute' => 'dcs_payment_cycle_code', 'value' => function($model) {
    return Yii::$app->controls->view_date($model->paymentCycleCode->from_date).' to '.Yii::$app->controls->view_date($model->paymentCycleCode->to_date);
},  'filter'=>false],
    //'sale_code',
    //'sale_date_time',
    /*[
        'attribute' => 'sale_date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        /*'value' => function($model) {
    return Yii::$app->controls->view_date($model->sale_date_time);
}],*/
        ['attribute' => 'main_amount', 'filter'=>false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'installment_amount', 'filter'=>false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'installment_status', 'filter'=>false],
];

$grid_option = [
    'id' => 'installment-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => false,
//        'deactivate' => function ($url, $model) {       
//        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Skip Payment'];
//        if($model->is_active==1 && $model->installment_status==0)
//        return GhostHtml::a('<i class="fa fa-close"></i>', ['/payment/tbl-product-sale/skip-installment', 'id' => $model->installment_code], $options);
//        },
        
        //'delete' => ['option' => 'name,plant_code,tbl-plant/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
</div>
    </div>
</div>