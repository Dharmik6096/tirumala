<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'android_installation_details_id', 'filter' => false],
    ['attribute' => 'mobile_no', 'filter' => false],
    ['attribute' => 'otp_code', 'filter' => false],
    ['attribute' => 'db_path', 'filter' => false],
    ['attribute' => 'imei_no', 'filter' => false],
    ['attribute' => 'sync_key', 'filter' => false],
    ['attribute' => 'db_version', 'filter' => false],
    ['attribute' => 'installation_type', 'value' => function($model) {
            return $model->installation_type == 0 ? 'Online' : 'Offline';
        }, 'filter' => false],
    ['attribute' => 'is_active', 'value' => function($model) {
            return $model->is_active == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'detail-list',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
