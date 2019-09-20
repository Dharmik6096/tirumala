<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['label' => Yii::t('app', 'DCS Code'), 'attribute' => 'organization_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_code');
        }, 'filter' => FALSE],
    ['label' => Yii::t('app', 'DCS'), 'attribute' => 'organization_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->androidInstallationCode, ['dcsCode'], 'dcs_name');
        }, 'filter' => TRUE],
//    ['attribute' => 'organization_type', 'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->androidInstallationCode, 'organization_type');
//        }, 'filter' => TRUE],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'device_id'],
    ['attribute' => 'db_version',
        'filter' => FALSE,
        'value' => function ($model) {
            return isset($model->db_version) ? Yii::$app->dropdown->getRecords('db_version')['data'][$model->db_version] : '';
        },],
    ['attribute' => 'installation_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('installation_type', $searchModel, 'installation_type'),
        'value' => function ($model) {
            return isset($model->installation_type) ? Yii::$app->dropdown->getRecords('installation_type')['data'][$model->installation_type] : '';
        },],
];

$grid_option = [
    'id' => 'android-installation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
//        'view' => true,
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
