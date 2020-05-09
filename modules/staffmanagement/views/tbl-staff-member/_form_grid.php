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
    ['attribute' => 'staff_member_name'],
    ['attribute' => 'designation_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
        }],
    [
        'attribute' => 'tenure_from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'width' => '200px',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_from_date);
        }],
    ['attribute' => 'mobile_no'],
    [
        'attribute' => 'payment_mode',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_mode_member', $searchModel, 'payment_mode'),
        'value' => function($model) {
            return isset($model->payment_mode) ? Yii::$app->dropdown->getRecords('payment_mode_member')['data'][$model->payment_mode] : '';
        }],
];
$grid_option = [
    'id' => 'staff-member-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/staffmanagement/tbl-staff-member/update', 'id' => $model->staff_member_code], $options);
        },
        'staff-member-design' => function ($url, $model) {
            $disable = '';
            $options = ['data-name' => $model->staff_member_code, 'class' => $disable, 'data-val' => $model->staff_member_code, 'title' => Yii::t('app', 'Designation')];
            return Html::a('<span><i class="fa fa-group"></i></span>', ['/staffmanagement/tbl-staff-member/staff-member-designation', 'id' => $model->staff_member_code], $options);
        },]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>