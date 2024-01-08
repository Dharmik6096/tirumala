<?php

$attribute = [
    ['attribute' => 'attendance_detail_code', 'filter' => false],
    ['attribute' => 'attendance_date', 'filter' => false],
    ['attribute' => 'in_time', 'value' => function($model) {
            return Yii::$app->controls->view_time($model->in_time);
        }, 'filter' => false],
    ['attribute' => 'out_time', 'value' => function($model) {
            return Yii::$app->controls->view_time($model->out_time);
        }, 'filter' => false],
    ['attribute' => 'in_desc', 'filter' => false],
    ['attribute' => 'out_desc', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'user-attendance-detail-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($userDetailDataProvider, $user_detail, $grid_option, '', false);
?>