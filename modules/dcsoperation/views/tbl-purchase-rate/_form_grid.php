<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Html;
?>

<div class="grid-search clearfix">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>

<?php
$attribute = [
    ['attribute' => 'purchase_rate_code', 'value' => 'purchase_rate_code',],
    ['attribute' => 'reference_code', 'value' => 'reference_code',],
    ['attribute' => 'dcs_purchase_rate_code'],
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
    ['attribute' => 'shift_id', 'value' => 'shiftId.shift',],
    ['attribute' => 'shift_applicability', 'value' => 'shiftApplicability.shift',],
    ['attribute' => 'rate_gen_method_code', 'value' => 'rateMethod.method',],
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
    'ts_rate',
    'description',
];

$grid_option = [
    'id' => 'purchase-rate-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'rowcolor' => 'danger',
    'actions' => [
        'view' => true,
//        'update_data' => function ($url, $model) {
//            $disable = ($model->is_active == 0) ? 'disabled' : '';
//            if ($disable == '' && $model->rate_gen_method_code == 3) {
//                $disable = 'disabled';
//            }
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $disable];
//
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/dcsoperation/tbl-purchase-rate-details/create-rate', 'id' => $model->purchase_rate_code, 'method' => $model->rate_gen_method_code], $options);
//        },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/dcsoperation/tbl-purchase-rate/purchase-rate-applicability', 'id' => $model->purchase_rate_code], $options);
        },
        'view_rate' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Rate Chart', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-bar-chart" aria-hidden="true"></i>', ['/dcsoperation/tbl-purchase-rate-details/rate-chart', 'id' => $model->purchase_rate_code, 'milk_type' => 1, 'rate_class' => 0, 'milk_quality' => 1], $options);
        },
        'export_rate_chart' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'Export Rate Chart')];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-purchase-rate-details/export-rate-chart', 'id' => $model->purchase_rate_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>