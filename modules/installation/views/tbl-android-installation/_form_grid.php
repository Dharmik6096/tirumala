<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['label' => Yii::t('app', 'DCS Code'),'attribute' => 'organization_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
        }, 'filter' => TRUE],
    ['label' => Yii::t('app', 'DCS'), 'attribute' => 'organization_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'dcs_name');
        }, 'filter' => TRUE],
//    ['attribute' => 'organization_type', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
//        }, 'filter' => TRUE],
    ['attribute' => 'mobile_no', 'filter' => TRUE],
    ['attribute' => 'device_id', 'filter' => TRUE],
    ['attribute' => 'db_version', 'filter' => TRUE],
    ['attribute' => 'installation_type', 'value' => function($model) {
            return $model->installation_type == 0 ? 'Online' : 'Offline';
        }, 'filter' => TRUE],
];

$grid_option = [
    'id' => 'android-installation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'download' => function ($url, $model) {
            $class = $model->installation_type == 1 ? '' : ' disabled ';
            $options = ['title' => Yii::t('app', 'Download'), 'class' => $class];
            $path = $model->db_path;
            return GhostHtml::a('<i class="fa fa-download"></i>', ['/installation/tbl-android-installation/download', 'id' => Yii::$app->basePath . $path], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
