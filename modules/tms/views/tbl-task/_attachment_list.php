<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
    ['attribute' => 'module_code', 'filter' => false],
    ['attribute' => 'module_name', 'filter' => false],
    ['attribute' => 'attachment_type', 'filter' => false],
    ['attribute' => 'file_name', 'filter' => false],
    ['attribute' => 'attachment', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
    ['attribute' => 'attachment', 'value' => function($model) {
            return Yii::$app->general->openImage($model->attachment);
        }, 'format' => 'raw', 'visible' => true],
];

$grid_option = [
    'id' => 'task-attachment-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($task_attachment, $attachment, $grid_option, '', false);
?>