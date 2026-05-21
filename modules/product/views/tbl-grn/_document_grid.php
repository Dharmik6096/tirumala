<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
    ['attribute' => 'doc_id', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
        }],
];

$grid_option = [
    'id' => 'document',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-attachment' => function ($url, $model) {
            $attachemnt = $model->attachment;
            $url = !empty($attachemnt) ? $attachemnt : '';
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View', 'target' => '_blank']);
        },
    ]
];

Yii::$app->grid->bind($dataProviderOther, $attachment, $grid_option, '', false);
?>