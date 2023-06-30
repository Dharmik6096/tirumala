<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'tab_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('tab_type', $searchModel, 'tab_type'),
        'value' => function ($model) {
            return isset($model->tab_type) ? Yii::$app->dropdown->getRecords('tab_type')['data'][$model->tab_type] : '';
        },],
    ['attribute' => 'sr_no'],
    ['attribute' => 'mac_address'],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'device-master-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
        'update' => true,
        'mapping' => function ($url, $model) {
            $options = ['data-name' => $model->device_master_code, 'data-val' => $model->device_master_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Device Master Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/globalmaster/tbl-device-master-mapping/create', 'id' => $model->device_master_code, 'srno' => $model->sr_no, 'type' => Yii::$app->dropdown->getRecords('tab_type')['data'][$model->tab_type]], $options);
        },
        'applock-config' => function ($url, $model) {
            $options = ['data-val' => $model->mac_address, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'APP Lock Config'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/configuration/tbl-app-lock-config/create', 'id' => $model->mac_address], $options);
        },
        'delete' => ['option' => 'mac_address,device_master_code,tbl-device-master/delete,disableDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
