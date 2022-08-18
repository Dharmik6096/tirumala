<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'label' => (Yii::t('app', 'MCC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'label' => (Yii::t('app', 'MCC Name')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false],
        ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    'chilling_rate',
    'primary_tpt_cost',
    'commission_percentage',
    'labour_charge',
    'service_charge',
        ['attribute' => 'headload_charge', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'building_rent_labour_charge', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'dgset_service_other_charge', 'filter' => false, 'visible' => FALSE],
    'other_charge_addition',
    'other_charge_deduction',
];

$grid_option = [
    'id' => 'milkcost-param-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
