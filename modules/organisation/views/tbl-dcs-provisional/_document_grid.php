<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'doc_id', 'value' => function($model) {
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
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank']);
        },
        'delete' => ['option' => 'attachment_code,attachment_code,/document/tbl-attachment/attachment-delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $attachment, $grid_option, '', false);
?>