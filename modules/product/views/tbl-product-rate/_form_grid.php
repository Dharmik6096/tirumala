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
//    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
//        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>

<?php
$attribute = [
    //'rate_code',
    ['attribute' => 'product_sale_rate_code', 'value' => 'product_sale_rate_code', 'visible' => false],
    [
        'attribute' => 'union_code', 'filter' => false,
        'value' => function($model) {
            return (!empty($model->union_code) || isset($model->unionCode)) ? $model->unionCode->union_name : '-';
        }],
    ['attribute' => 'product_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }],
    'sale_rate',
    [
        'label' => 'Rate for Gyan',
        'attribute' => 'rate_wharehouse',
        'value' => 'rate_wharehouse',
        'visible' => Yii::$app->session->get('eiplCode') == 'GYAN' ? true : false,
    ],
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
    ['attribute' => 'is_member_rate', 'filter' => false, 'value' => function($model) {
            return $model->is_member_rate == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }],
    ['attribute' => 'commission', 'value' => 'commission'],
    ['attribute' => 'rdo_commission',
            'value' => function($model) {
                return (!empty($model->rdo_commission)) ? $model->rdo_commission : '.00';
            }
    ],
];

$grid_option = [
    'id' => 'product-rate-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view_details' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-product-rate/view', 'id' => $model->product_sale_rate_code, 'is_member_rate' => $model->is_member_rate], $options);
        },
        'applicabilty' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-rate/product-rate-applicability', 'id' => $model->product_sale_rate_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
