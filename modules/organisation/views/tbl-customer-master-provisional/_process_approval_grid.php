<?php

$attribute = [
    ['attribute' => 'level', 'filter' => false],
    ['attribute' => 'approval_mode', 'label' => 'Mode', 'filter' => false],
    ['attribute' => 'user_code', 'label' => 'User', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false
    ],
    ['attribute' => 'login_type', 'filter' => false],
    ['attribute' => 'department', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->departmentId, 'department');
        }, 'filter' => false
    ],
    ['attribute' => 'updated_by', 'label' => 'Status By', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->updatedBy, 'name');
        }, 'filter' => false
    ],
    ['attribute' => 'status', 'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('approval_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model->status] : '';
        }, 'filter' => false],
    ['attribute' => 'created_at', 'label' => 'Date', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }, 'filter' => false],
    ['attribute' => 'department', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'process-approval-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($processApprovalDataProvider, $processApprovalModel, $grid_option);
?>