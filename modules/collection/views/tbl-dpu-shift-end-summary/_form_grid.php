<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'BMCCode', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
    ['attribute' => 'VillageCode', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
    ['attribute' => 'dtdate',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dtdate);
}],
    ['attribute' => 'shift', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }],
    ['attribute' => 'samplecount', 'filter' => false],
    ['attribute' => 'Qty', 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];

$grid_option = [
    'id' => 'dpu-shift-end-summary-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
