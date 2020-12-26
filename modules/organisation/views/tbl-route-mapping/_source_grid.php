<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'from_dest', 'value' => function($model) use($modelRouteSource) {
            return $modelRouteSource->getDestinationName($model->from_type, $model->from_dest) . '(' . $modelRouteSource->getDestinationName($model->from_type, $model->from_dest, 'ref_code') . ')' . ' - ' . Yii::t('app', strtoupper($model->from_type));
        }, 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'from_type,route_mapping_source_code,tbl-route-mapping/delete-source'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>