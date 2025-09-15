<?php

use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'disease_id', 'filter' => true],
    ['attribute' => 'disease_name', 'filter' => true],
];
$grid_option = [
    'id' => 'disease-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'mapping' => function ($url, $model) {
            $options = ['data-name' => $model->disease_name, 'data-val' => $model->disease_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Symptom Mapping'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/veterinary/tbl-disease-master/map-route-source', 'id' => $model->disease_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
