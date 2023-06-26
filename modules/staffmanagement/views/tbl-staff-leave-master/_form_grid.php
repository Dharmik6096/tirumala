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
    [
        'attribute' => 'leave_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('leave_type', $searchModel, 'leave_type'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->leave_type, 'leave_type');
        }],
    [
        'attribute' => 'leave_for',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_on_role', $searchModel, 'leave_for'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->leave_for, 'is_on_role');
        }],
    [
        'attribute' => 'is_half',
        'vAlign' => 'middle',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_type', $searchModel, 'is_half'),
        'value' => function($model) {
            return ($model->is_half == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }
    ],
];
$grid_option = [
    'id' => 'staff-leave-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = $model->is_default == 1 ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => $disable, 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/staffmanagement/tbl-staff-leave-master/update', 'id' => $model->staff_leave_code], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>