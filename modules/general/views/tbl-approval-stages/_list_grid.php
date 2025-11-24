<?php

use kartik\grid\GridView;
?>

<div class="">
    <h5 class="panel-heading"><?= Yii::t('app', 'Approval Stages Details') ?></h5>

    <?php
    $attribute = [
            ['attribute' => 'level', 'filter' => FALSE],
            ['attribute' => 'level_priority', 'filter' => FALSE],
            ['attribute' => 'approval_mode',
            'filter' => FALSE,
            'value' => function ($model) {
                return isset($model->approval_mode) ? Yii::$app->dropdown->getRecords('approval_mode')['data'][$model->approval_mode] : '';
            },],
            ['attribute' => 'approval_type',
            'filter' => FALSE,
            'value' => function ($model) {
                return isset($model->approval_type) ? Yii::$app->dropdown->getRecords('approval_type')['data'][$model->approval_type] : '';
            },],
            ['attribute' => 'login_type',
            'filter' => FALSE,
            'value' => function ($model) {
                return !empty($model->login_type) ? Yii::$app->dropdown->getRecords('route_login_type')['data'][$model->login_type] : '';
            },],
            ['attribute' => 'department', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->departmentId, 'department');
            }, 'filter' => false],
            ['attribute' => 'user_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->userCode, 'name');
            }, 'filter' => false],
    ];


    $grid_option = [
        'id' => 'approval-detail-list-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>

</div>