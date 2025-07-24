<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'document',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-attachment' => function ($url, $model) {
            $attachemnt = $model->attachment;
            $url = !empty($attachemnt) ? $attachemnt : '';
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank']);
        },
        'view-attachment' => function ($url, $model) {
            $status = $model->getFileName($model->file_name);
            if (isset($status)) {
                $attachemnt = $status['attachment'];
            }
            $url = !empty($attachemnt) ? $attachemnt : '';
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank']);
        },
    ]
];

Yii::$app->grid->bind($dataProviderOther, $attachment, $grid_option, '', false);
?>