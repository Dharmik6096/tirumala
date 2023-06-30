<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<div class="grid-search">
    <?php
//    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
//        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>

<?php
$attribute = [
    //'rate_code',
        ['attribute' => 'product_purchase_rate_code', 'visible' => false, 'value' => 'product_purchase_rate_code'],
        [
        'attribute' => 'union_code', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }],
        ['attribute' => 'product_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }],
    'purchase_rate',
        [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
];

$grid_option = [
    'id' => 'product-purchase-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view_details' => function ($url, $model) {
            $class = '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-product-purchase-rate/view', 'id' => $model->product_purchase_rate_code], $options);
        },
        'applicabilty' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-purchase-rate/product-purchase-rate-applicability', 'id' => $model->product_purchase_rate_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
