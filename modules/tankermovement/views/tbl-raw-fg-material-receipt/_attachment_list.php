<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
        ['attribute' => 'attachment_type', 'filter' => false],
        ['attribute' => 'file_name', 'filter' => false],
        ['attribute' => 'remarks', 'filter' => false],
        ['attribute' => 'attachment', 'value' => function($model) {
            return Yii::$app->general->openImage($model->attachment);
        }, 'format' => 'raw', 'visible' => true],
];

$grid_option = [
    'id' => 'receipt-attachment-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($receipt_attachment, $attachment, $grid_option, '', false);
?>