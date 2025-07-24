<?php
use kartik\grid\GridView;
$attribute = [
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code'), 'filter' => false],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle'],
        [
        'attribute' => 'status_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->indentCode->status_date);
        },'filter' => false],
        ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        },'filter' => false],
        [
        'attribute' => 'challan_date', 'visible' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->challan_date);
        }, 'filter' => false],
        ['attribute' => 'dispatch_qty','filter' => false],
];

$grid_option = [
    'id' => 'indent-dispatch-list',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>