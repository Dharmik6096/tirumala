<?php

use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'committee_type_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->committeeType, 'committee_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => 'Name', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'member_code',],
    ['attribute' => 'member_name'],
    [
        'attribute' => 'election_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->election_date);
        }],
    [
        'attribute' => 'tenure_from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_from_date);
        }],
    [
        'attribute' => 'tenure_to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_to_date);
        }],
    ['attribute' => 'formation', 'filter' => false, 'visible' => false, 'value' => function($model) {
        return Yii::$app->controls->view_date($model->formation);
    }],
    ['attribute' => 'joining_date', 'filter' => false, 'visible' => false, 'value' => function($model) {
        return Yii::$app->controls->view_date($model->joining_date);
    }],
    ['attribute' => 'registration_date', 'filter' => false, 'visible' => false, 'value' => function($model) {
        return Yii::$app->controls->view_date($model->registration_date);
    }],
    ['attribute' => 'committee_code', 
    'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->committeeCode, 'committee_name');
        },
    'filter' => false, 'visible' => false],
];
$grid_option = [
    'id' => 'committee-members-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>