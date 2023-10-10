<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
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
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-attachment' => function ($url, $model) {
            $path = Yii::$app->params['complaint_dir_path'];
            $attachemnt = '/' . $path . $model->file_name;
            $url = Url::to([$attachemnt]);
            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View']);
        },
    ]
];

Yii::$app->grid->bind($attachmentDataProvider, $complain_attachment, $grid_option, '', false);
?>