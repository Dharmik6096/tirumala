<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'filter' => true],
    ['attribute' => 'is_mcc', 'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_mcc');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_mcc'),],
    ['attribute' => 'is_warehouse', 'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_warehouse');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_warehouse'),],
    ['attribute' => 'qty'],
];

$grid_option = [
    'id' => 'indent-product-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = '';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/product/tbl-indent-product/update', 'id' => $model->indent_product_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
