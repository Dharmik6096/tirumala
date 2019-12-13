<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php
$attribute = [
    ['attribute' => 'p_bmc_code', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'bmcCode.bmc_name,bmc_mapping_code,tbl-dcs-bmc/delete-bmc'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>