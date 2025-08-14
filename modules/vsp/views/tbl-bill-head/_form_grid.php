<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class="grid-search clearfix">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,
        'filter' => false,],
        ['attribute' => 'bill_head_code', 'visible' => false],
        ['attribute' => 'bill_head_name'],
        ['attribute' => 'bill_head_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('calc_type', $searchModel, 'bill_head_type'),
        'value' => function($model) {
            return isset($model->bill_head_type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->bill_head_type] : 'N/A';
        },],
        ['attribute' => 'calculation_based_on',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('calc_based_on', $searchModel, 'calculation_based_on'),
        'value' => function($model) {
            return !empty($model->calculation_based_on) ? Yii::$app->dropdown->getRecords('calc_based_on')['data'][$model->calculation_based_on] : 'N/A';
        },],
        ['attribute' => 'default_bill_head_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->defaultBillHeadCode, 'default_bill_head_name');
        },],
        ['attribute' => 'general_formula', 'value' => function($model) {
            return '<div>' . $model->general_formula . '</div>';
        }, 'format' => 'raw'],
        ['attribute' => 'bill_head_for',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('bill_head_for', $searchModel, 'bill_head_for'),
        'value' => function($model) {
            return isset($model->bill_head_for) ? Yii::$app->dropdown->getRecords('bill_head_for')['data'][$model->bill_head_for] : 'N/A';
        },],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'sequence_no'],
        ['attribute' => 'has_slab',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'has_slab'),
        'value' => function($model) {
            return $model->has_slab == 1 ? 'Yes' : 'No';
        },],
        ['attribute' => 'is_hold',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_hold'),
        'value' => function($model) {
            return $model->is_hold == 1 ? 'Yes' : 'No';
        },],
        ['attribute' => 'payment_cycle_type'],
];
$grid_option = [
    'id' => 'bill-head-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = !empty($model->billHeadCode) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/vsp/tbl-bill-head/update', 'id' => $model->bill_head_code], $options);
        },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0 || $model->has_slab == 1) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/vsp/tbl-bill-head/bill-head-applicability', 'id' => $model->bill_head_code], $options);
        },
        'update_to_date_applicability' => function ($url, $model) {
            $disable = ($model->is_active == 0 || $model->has_slab == 1) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Update To Date Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-share"></i>', ['/vsp/tbl-bill-head/update-to-date-applicability', 'id' => $model->bill_head_code], $options);
        },
        'hold_release_applicability' => function ($url, $model) {
            $disable = ($model->is_active == 1 && $model->is_hold == 1) ? '' : 'disabled';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Hold Release Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="far fa-money-bill-alt"></i>', ['/vsp/tbl-bill-head/hold-release-applicability', 'id' => $model->bill_head_code], $options);
        },
        // 'update_to_date' => function ($url, $model) {
        //     $disable = ($model->is_active == 0 || $model->has_slab == 1) ? 'disabled' : '';
        //     $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit To Date', 'class' => $disable];
        //     return GhostHtml::a('<i class="fas fa-pen-square"></i>', ['/vsp/tbl-bill-head/update-to-date', 'id' => $model->bill_head_code], $options);
        // },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>