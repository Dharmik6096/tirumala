<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'device_temp_name', 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'device-config-template-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => TRUE,
        'mapping' => function ($url, $model) {
            $disable = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/configuration/tbl-device-config-template/device-config-mapping-applicability', 'id' => $model->device_temp_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
