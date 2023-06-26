<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
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
        }, 'filter' => false],
    [
        'attribute' => 'leave_from',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'width' => '200px',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->leave_from);
        }],
    [
        'attribute' => 'leave_to',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'width' => '200px',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->leave_to);
        }],
    [
        'attribute' => 'leave_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('leave_type', $searchModel, 'leave_type'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->leave_type, 'leave_type');
        }],
    [
        'attribute' => 'lwp_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('lwp_type', $searchModel, 'lwp_type'),
        'value' => function($model) {
            return isset($model->lwp_type) ? Yii::$app->dropdown->getRecords('lwp_type')['data'][$model->lwp_type] : '';
        }],
    ['attribute' => 'leave_count'],
    ['attribute' => 'remark', 'filter' => FALSE],
];
$grid_option = [
    'id' => 'staff-attendence-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = $model->salaryDisburse() ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => $disable, 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/staffmanagement/tbl-staff-attendance/update', 'id' => $model->staff_attendance_code], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>