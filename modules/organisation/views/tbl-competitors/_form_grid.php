<?php

use app\modules\usermanagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'competitor_id'],
    ['attribute' => 'competitor_name'],
];

$grid_option = [
    'id' => 'competitors-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'applicabilty' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Competitor Mapping'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/organisation/tbl-competitors/competitor-mapping', 'id' => $model->competitor_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
