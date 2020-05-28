<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false, 'visible' => FALSE],
    ['attribute' => 'staff_member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
        }],
    [
        'attribute' => 'wef_date',
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'minViewMode' => 'months',
                'format' => 'mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_month($model->wef_date);
        }],
    ['attribute' => 'addition',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->addition);
        },
    ],
    ['attribute' => 'deduction',
        'label' => Yii::t('app', 'Deduction'),
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->deduction);
        },
    ],
    ['attribute' => 'total_value',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->addition - $model->deduction);
        }],
];

$grid_option = [
    'id' => 'web-staff-salary-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $disable = $model->salaryDisburse() ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => $disable, 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/staffmanagement/tbl-staff-salary/update', 'id' => $model->staff_salary_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>