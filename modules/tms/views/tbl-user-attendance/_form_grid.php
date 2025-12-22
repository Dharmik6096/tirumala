<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'state_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->areaBmcMapping, ['mainAreaCode', 'stateCode'], 'state_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'region_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->areaBmcMapping, ['mainAreaCode', 'regionCode'], 'region_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'area_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->areaBmcMapping, ['mainAreaCode'], 'area_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'plant_code', 'label' => Yii::t('app', 'PLANT'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'User'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'visible' => true, 'filter' => true],
    ['attribute' => 'user_code',
        'label' => Yii::t('app', 'Login Type'),
        'filter' => FALSE,
        'visible' => true,
        'value' => function ($model) {
            $login_type = Yii::$app->general->getforeignkey($model->userCode, 'login_type');
            return isset($login_type) ? (!empty(Yii::$app->dropdown->getRecords('login_type')['data'][$login_type]) ? Yii::$app->dropdown->getRecords('login_type')['data'][$login_type] : '') : '';
        }],
    ['attribute' => 'user_code',
    'label' => Yii::t('app', 'Department'),
    'value' => function($model) {
        return Yii::$app->general->getmultiforeignkey($model->userCode, ['departmentCode'], 'department');
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'user_code', 'label' => Yii::t('app', 'Employee Id'), 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->userCode, 'employee_id');
    }, 'visible' => true, 'filter' => false],
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
    ['attribute' => 'in_lat_long', 'visible' => false, 'filter' => false],
    ['attribute' => 'out_lat_long', 'visible' => false, 'filter' => false],
    ['attribute' => 'in_desc', 'filter' => false],
    ['attribute' => 'out_desc', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
    ['attribute' => 'duration', 'filter' => false],
    ['attribute' => 'api_status', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('send_status', $model, 'api_status');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('send_status', $searchModel, 'api_status')],
    ['attribute' => 'pick_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->pick_datetime);
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'response_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'response_msg', 'filter' => false],
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
