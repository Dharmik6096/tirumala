<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    [
        'attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    [
        'attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    [
        'attribute' => 'locking_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->locking_date);
        }],
    ['attribute' => 'total_count'],
//    ['attribute' => 'type'],
];

$grid_option = [
    'id' => 'loan-product-sale-lock-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'download_lock_sale' => function ($url, $model) {
            $class = '';
            $options = ['title' => Yii::t('app', 'Export Lock Sale'), 'class' => $class, 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/payment/tbl-loan-product-sale-locking/export-product-sale', 'id' => $model->locking_code], $options);
        },
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>