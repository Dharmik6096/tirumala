<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true,],
        ['attribute' => 'plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }, 'visible' => true,],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'visible' => true,],
        ['attribute' => 'bmc_code', 'value' => function($model) {
                return !empty($model->bmc_code) ? Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') : 'NA';
            }, 'visible' => true,],
        ['attribute' => 'dcs_code', 'value' => function($model) {
                return !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : 'NA';
            }, 'visible' => true,],
        ['attribute' => 'adjustment_type', 'filter' => false, 'visible' => true],
        [
            'attribute' => 'transaction_date',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->transaction_date);
            }
        ],
        ['attribute' => 'remarks', 'visible' => true],
];

$grid_option = [
    'id' => 'product-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view_details' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-product-stock-adjustment/view', 'id' => $model->product_stock_adjustment_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
