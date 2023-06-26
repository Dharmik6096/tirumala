<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        [
        'attribute' => 'wef_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
            ]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }, 'filter' => false],
        ['attribute' => 'min_pouring_day', 'filter' => false],
        ['attribute' => 'min_pouring_qty', 'filter' => false],
        ['attribute' => 'scheme_value', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-scheme-criteria-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($ddataProvider, $dsearchModel, $grid_option);
?>

