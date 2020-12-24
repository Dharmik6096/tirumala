<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    [
        'attribute' => 'from_date', 'filter' => FALSE,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        },
//                'label' => Yii::t('app', 'From Date')
    ],
    [
        'attribute' => 'to_date', 'filter' => FALSE,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'label' => Yii::t('app', 'To Date')],
];

$grid_option = [
    'id' => 'dcs-deactivation-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>