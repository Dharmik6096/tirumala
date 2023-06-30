<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
    //'dcs_payment_cycle_code',
    ['attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'filter' => false],
        ['attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'filter' => false],
    'interval_value'
];

$grid_option = [
    'id' => 'payment-cycle-grid',
    'attributes' => $attribute,
    'active_column' => TRUE,
    'actions' => [
//        'view' => true,
        'applicabilty' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/payment/tbl-payment-cycle/payment-cycle-applicability', 'id' => $model->payment_cycle_code], $options);
        },
        'delete' => ['option' => 'payment_cycle_code,payment_cycle_code,tbl-payment-cycle/delete,disableDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>