<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->bmcCode, ['tblMccPlant'], 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => 'Shift Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->shift_date);
        }],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
    ['attribute' => 'avg_fat', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'avg_snf', 'filter' => false],
    ['attribute' => 'quantity', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];





$grid_option = [
    'id' => 'shift-summary-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
