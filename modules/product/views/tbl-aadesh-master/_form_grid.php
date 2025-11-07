<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'aadesh_master_code', 'value' => 'aadesh_master_code', 'visible' => false],
    ['attribute' => 'union name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
    ['attribute' => 'product_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }],
    'sale_rate',
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
    'product_mrp',
    'distributor_landing_rate',
    'sachiv_price',
    'member_price',
];

$grid_option = [
    'id' => 'aadesh-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view_details' => function ($url, $model) {
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => $class];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/product/tbl-aadesh-master/view', 'id' => $model->aadesh_master_code, 'is_member_rate' => $model->is_member_rate], $options);
        },
        'applicabilty' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-aadesh-master/product-rate-applicability', 'id' => $model->aadesh_master_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
