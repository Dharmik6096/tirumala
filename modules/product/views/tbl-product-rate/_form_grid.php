<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<div class="grid-search">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions')))>1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>

<?php
$attribute = [
    //'rate_code',
    ['attribute' => 'product_rate_code','value' => 'product_rate_code'],
    ['attribute' => 'product_code',
     'value' => 'productCode.product_name'],
    'rate',
    [
    'attribute' => 'wef_date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->wef_date);
    }],
    [
        'attribute' => 'union_code', 'filter' => false,
        'value' => function($model) {
            return (!empty($model->union_code) || isset($model->unionCode)) ? $model->unionCode->union_name : '-';
    }],
];

$grid_option = [
    'id' => 'product-rate-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = ''; //$model->disableProduct()? '' : 'disabled';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-product-rate/update', 'id' => $model->product_rate_code], $options);
        },
        'delete' => ['option' => 'product_rate_code,product_rate_code,tbl-product-rate/delete'],
//        'update' => true,        
//        'delete' => ['option' => 'product_rate_code,product_rate_code,tbl-product-rate/delete'],
        /*'mapping' => function ($url, $model) {      
        $class = ''; //$model->disableProduct()? '' : 'disabled';
        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $class];
        return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-rate/product-rate-applicability', 'id' => $model->product_rate_code], $options);
        },*/
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
