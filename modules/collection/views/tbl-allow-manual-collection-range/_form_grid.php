<?php

use kartik\grid\GridView;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'from_date', 'filter' => true,
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
        }, 'filter' => FALSE],
        ['attribute' => 'to_date', 'filter' => true,
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
        }, 'filter' => FALSE],
        ['attribute' => 'table_name', 'filter' => true],
        ['attribute' => 'entry_type', 'filter' => false],
        ['attribute' => 'application_type', 'filter' => false],
        ['attribute' => 'is_weight_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_weight_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
        },],
        ['attribute' => 'is_quality_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_quality_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
        },],
        ['attribute' => 'is_approved', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approved]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approved] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_approved')],
        ['attribute' => 'approval_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('manual_approve_status', $searchModel, 'approval_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '';
        }],
];

$grid_option = [
    'id' => 'allow-collection-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
