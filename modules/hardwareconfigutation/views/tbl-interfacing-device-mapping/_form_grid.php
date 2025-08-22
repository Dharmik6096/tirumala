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
        ['attribute' => 'union_code', 'label' => Yii::t('app', 'Company'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'label' => 'Plant', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'label' => 'MCC', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false],
        ['attribute' => 'bmc_code', 'label' => 'BMC', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => 'DCS', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'weight_device_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->weightDeviceCode, 'device_name');
        }],
        ['attribute' => 'analyzer_device_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->analyzerDeviceCode, 'device_name');
        }],
        ['attribute' => 'printer_device_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->printerDeviceCode, 'device_name');
        }],
        ['attribute' => 'display_device_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->displayDeviceCode, 'device_name');
        }],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = Yii::$app->general->allowUpdateDelete($model) ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/hardwareconfigutation/tbl-interfacing-device-mapping/update', 'id' => $model->interfacing_device_mapping_code], $options);
        },
        'delete' => ['option' => 'interfacing_device_mapping_code,interfacing_device_mapping_code,tbl-interfacing-device-mapping/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
