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
        ['label' => 'Document', 'attribute' => 'doc_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
        }],
];

$grid_option = [
    'id' => 'document',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-attachment' => function ($url, $model) {
            $path = Yii::$app->params['document_upload'];
            $attachemnt = '/' . $path . $model->file_name;
            $url = Url::to([$attachemnt]);
            return Html::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View']);
        },
        'delete' => ['option' => 'attachment_code,attachment_code,/document/tbl-attachment/attachment-delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $attachment, $grid_option, '', false);
?>