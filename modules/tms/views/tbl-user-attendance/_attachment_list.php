<?php

$attribute = [
    ['attribute' => 'module_name', 'label' => 'Image Type',
        'value' => function($model) {
            return ($model->module_name == 'tbl_user_attendance_in') ? 'IN' : 'OUT';
        }, 'filter' => false],
    ['attribute' => 'attachment_type', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
    ['attribute' => 'attachment', 'value' => function($model) {
            return Yii::$app->general->openImage($model->attachment);
        }, 'format' => 'raw', 'visible' => true],
];

$grid_option = [
    'id' => 'user-attendance-attachment-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($attachmentDataProvider, $user_attachment, $grid_option, '', false);
?>