<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'organization_code', 'filter' => TRUE],
    ['attribute' => 'organization_type', 'filter' => TRUE],
];

$grid_option = [
    'id' => 'android-installation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
