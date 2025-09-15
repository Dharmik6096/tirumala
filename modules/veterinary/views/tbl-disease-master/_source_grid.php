<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php
$attribute = [
    ['attribute' => 'symptom_id'],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
//        'delete' => ['option' => 'from_type,route_mapping_source_code,tbl-route-mapping/delete-source'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>