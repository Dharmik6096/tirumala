<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false,],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false,],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => false,],
    ['attribute' => 'end_point'],
    ['attribute' => 'request_url'],
    ['attribute' => 'request_desc'],
    ['attribute' => 'txn_type'],
    ['attribute' => 'date1'],
    ['attribute' => 'date2'],
    ['attribute' => 'desc1'],
    ['attribute' => 'desc2'],
    ['attribute' => 'request_header'],
    ['attribute' => 'request_payload'],
    ['attribute' => 'response_payload'],
    ['attribute' => 'request_timestamp'],
    ['attribute' => 'response_timestamp'],
    ['attribute' => 'status_code'],
    ['attribute' => 'status_type'],
    ['attribute' => 'status_response'],
    ['attribute' => 'status_message'],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            $disable = ($model->status_response == 'success' || $model->txn_type == 'cargill') ? 'disabled' : '';
            $options = ['title' => Yii::t('app', 'Repush'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-share-square-o"></i>', ['/clienterp/tbl-client-erp-api-log/repush', 'id' => $model->log_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
