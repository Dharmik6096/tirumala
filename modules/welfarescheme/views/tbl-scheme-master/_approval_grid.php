
<?php

$attribute = [
        ['attribute' => 'level', 'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->level, 'approval_level');
        }, 'filter' => FALSE],
        ['attribute' => 'approval_mode', 'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->approval_mode, 'approval_mode');
        }, 'filter' => FALSE],
        ['attribute' => 'user_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name') . '-' . Yii::$app->general->getforeignkey($model->userCode, 'department');
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'approval-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'user_code,stage_id,tbl-scheme-master/delete-approval-stage'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>