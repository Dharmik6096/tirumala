<?php

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'from_date', 'value' => function ($model) {
        return Yii::$app->controls->view_date($model->from_date);
    }, 'filter' => FALSE],
    ['attribute' => 'from_shift', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
    }, 'filter' => FALSE],
    ['attribute' => 'to_date', 'value' => function ($model) {
        return Yii::$app->controls->view_date($model->to_date);
    }, 'filter' => FALSE],
    ['attribute' => 'to_shift', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->toShift, 'shift');
    }, 'filter' => FALSE],
    ['attribute' => 'entry_type', 'filter' => false],
    ['attribute' => 'application_type', 'filter' => false],
    ['attribute' => 'is_weight_manual', 'value' => function ($model) {
        return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
    }, 'filter' => false],
    ['attribute' => 'is_quality_manual', 'value' => function ($model) {
        return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
    }, 'filter' => false],
    ['attribute' => 'approval_status', 'value' => function ($model) {
        return isset(Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '';
    }, 'filter' => false],
    ['label' => Yii::t('app', 'Contact Person'), 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->complainCode, 'contact_person');
    }, 'visible' => true, 'filter' => false],
    ['label' => Yii::t('app', 'Mobile No'), 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->complainCode, 'mobile_no');
    }, 'visible' => true, 'filter' => false],
    ['label' => Yii::t('app', 'Complain Status'), 'format' => 'raw', 'value' => function ($model) {
        $complain_status = Yii::$app->general->getforeignkey($model->complainCode, 'complain_status');
        $status = isset(Yii::$app->dropdown->getRecords('complain_status')['data'][$complain_status]) ? Yii::$app->dropdown->getRecords('complain_status')['data'][$complain_status] : '';
        return Html::a($status, ['/complaint/tbl-complain/view', 'id' => $model->complain_code], ['target' => '_blank']);
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'remark', 'filter' => false],
];

$grid_option = [
    'id' => 'complain-activity',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
