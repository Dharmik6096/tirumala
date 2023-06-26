<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false, 'visible' => FALSE],
    ['attribute' => 'staff_member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
        }, 'filter' => false],
    [
        'attribute' => 'app_from_date',
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'minViewMode' => 'months',
                'format' => 'mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_month($model->app_from_date);
        }],
    ['attribute' => 'tr_date',
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->tr_date);
        },
    ],
    ['attribute' => 'type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('type', $searchModel, 'type'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->type, 'type');
        }
    ],
    ['attribute' => 'amount',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->amount);
        },
    ],
    ['attribute' => 'installment_no',],
    ['attribute' => 'remark', 'visible' => FALSE],
];
$grid_option = [
    'id' => 'web-staff-attend-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $disable = $model->salaryDisburse() ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => $disable, 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/staffmanagement/tbl-staff-addition-deduction/update', 'id' => $model->staff_addition_deduction_no], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
        