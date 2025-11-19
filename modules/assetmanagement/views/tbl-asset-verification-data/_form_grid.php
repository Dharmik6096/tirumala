<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => FALSE, 'vAlign' => 'middle','visible'=>false],
    ['attribute' => 'customer_type', 'label' => Yii::t('app', 'Type'), 'filter' => false],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'filter' => false],
    ['attribute' => 'asset_group_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->assetGroupCode, 'asset_group_name');
    }, 'visible' => true],
    ['attribute' => 'asset_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->assetCode, 'asset_name');
    }],
    ['attribute' => 'serial_number', 'visible' => true, 'filter' => false],
    ['attribute' => 'manufacturer_serial_number', 'visible' => true, 'filter' => false],
    ['attribute' => 'asset_verification_code', 'visible' => true, 'filter' => false],
    ['attribute' => 'is_verified', 'value' => function($model) {
        return $model->is_verified == 1 ? 'Verified' : 'Not Verified';
    }, 'filter' => false],
    ['attribute' => 'verification_date', 'attribute' => 'verification_date',
        'value' => function($model) {
            return (!empty($model->verification_date)) ? Yii::$app->controls->view_date($model->verification_date) : 'N/A';
        }, 'filter' => false],
    ['attribute' => 'user_name', 'label' =>  Yii::t('app', 'Employee Name'), 'value' => function ($model) {
            return $model->customer_type == 'USER' ? Yii::$app->general->getforeignkey($model->userCode, 'name') : '';
        }, 'filter' => false
    ],
    ['attribute' => 'date_of_cap', 'value' => function($model) {
        return (!empty($model->date_of_cap)) ? Yii::$app->controls->view_date($model->date_of_cap) : 'N/A';
    }, 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
    ['attribute' => 'scan_status', 'value' => function($model) {
        return $model->scan_status == 1 ? Yii::t('app', 'Asset Physically Scan') : Yii::t('app', 'Asset Physically NOT Scan');
    }, 'filter' => false],
    ['attribute' => 'physical_verification_status', 'value' => function($model) {
        return $model->physical_verification_status == 1 ? Yii::t('app', 'Physically Available') : Yii::t('app', 'Physically Not Available');
    }, 'filter' => false],
];

$grid_option = [
    'id' => 'asset-verification-list',
    'attributes' => $attribute,
    'active_column' => false
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
