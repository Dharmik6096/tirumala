<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'stationId', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
    ['attribute' => 'shift', 'filter' => false],
    ['attribute' => 'milkType', 'filter' => false],
    ['attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->date);
}],
    ['attribute' => 'quantity', 'filter' => false],
    ['attribute' => 'quantityMode', 'filter' => false],
    ['attribute' => 'rate', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];

$grid_option = [
    'id' => 'local-milk-sale-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
