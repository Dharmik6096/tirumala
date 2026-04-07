<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
        ['attribute' => 'module_code', 'filter' => false],
        ['attribute' => 'module_name', 'filter' => false],
        ['attribute' => 'attachment_type', 'filter' => false],
        ['attribute' => 'file_name', 'filter' => false],
        ['attribute' => 'attachment', 'filter' => false],
        ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-attachment' => function ($url, $model) {
            $attachemnt = $model->attachment;
            $url = !empty($attachemnt) ? $attachemnt : '';
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank']);
        },
    ]
];

Yii::$app->grid->bind($attachmentDataProvider, $attachment, $grid_option, '', false);
?>