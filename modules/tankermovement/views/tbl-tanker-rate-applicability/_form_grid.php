<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'applicable_code'],
    ['attribute' => 'applicable_for'],
    ['attribute' => 'party_name', 'value' => 'partyCode.party_name'],
    ['attribute' => 'tanker_rate_code', 'filter'=>false],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
];
$grid_option = [
    'id' => 'member-download-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' =>['option' => 'applicable_code,rate_app_code,tbl-tanker-rate-applicability/delete']
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>