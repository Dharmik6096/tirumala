<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'organization_code', 'filter' => TRUE],
    ['attribute' => 'organization_type', 'filter' => TRUE],
    ['attribute' => 'mobile_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->installDetail, 'mobile_no');
        }, 'filter' => FALSE],
    ['attribute' => 'device_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->installDetail, 'device_id');
        }, 'filter' => FALSE],
    ['attribute' => 'db_version', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->installDetail, 'db_version');
        }, 'filter' => FALSE],
    ['attribute' => 'db_version', 'value' => function($model) {
            $installType = Yii::$app->general->getforeignkey($model->installDetail, 'installation_type');
            return $installType == 0 ? 'Online' : 'Offline';
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'android-installation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'download' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Download')];
            $path = Yii::$app->general->getforeignkey($model->installDetail, 'db_path');
            return GhostHtml::a('<i class="fa fa-download"></i>', ['/installation/tbl-android-installation/download', 'id' => Yii::$app->basePath . $path], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
