<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\components\GeneralFunctions;
?>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'label' => Yii::t('app', 'PLANT'),'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'),'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'User'),'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->userCode, 'name');
    }, 'visible' => true, 'filter' => true],
    ['attribute' => 'attendance_date', 'value' => function($model) {
        return Yii::$app->controls->view_date($model->attendance_date);
    }, 'filter' => false],
    ['attribute' => 'in_time', 'value' => function($model) {
        return Yii::$app->controls->view_time($model->in_time);
    }, 'filter' => false],

    ['attribute' => 'out_time', 'value' => function($model) {
        return Yii::$app->controls->view_time($model->out_time);
    }, 'filter' => false],
    ['attribute' => 'day_count', 'filter' => false],
    ['attribute' => 'in_lat_long', 'visible' => false,'filter' => false],
    ['attribute' => 'out_lat_long', 'visible' => false, 'filter' => false],
    ['attribute' => 'in_desc', 'filter' => false],
    ['attribute' => 'out_desc', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],

];
$grid_option = [
    'id' => 'user-attendance-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
