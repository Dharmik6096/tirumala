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
    'id' => 'user-attendance-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'Attachment_In' => function ($url, $model) {
            $inAttachmentUrl = Yii::$app->general->getAttachmentUrl('tbl_user_attendance_in', $model->module_code);
            $outAttachmentUrl = Yii::$app->general->getAttachmentUrl('tbl_user_attendance_out', $model->module_code);
            $inAttachmentIcon = '';
            if ($inAttachmentUrl) {
                $inAttachmentIcon = Html::a(
                                '<span class="glyphicon glyphicon-picture"></span>', $inAttachmentUrl, [
                            'title' => Yii::t('yii', 'In Attachment'),
                            'target' => '_blank',
                                ]
                );
            }

            $outAttachmentIcon = '';
            if ($outAttachmentUrl) {
                $outAttachmentIcon = Html::a(
                                '<span class="glyphicon glyphicon-picture"></span>', $outAttachmentUrl, [
                            'title' => Yii::t('yii', 'Out Attachment'),
                            'target' => '_blank',
                                ]
                );
            }
            return $inAttachmentIcon . ' ' . $outAttachmentIcon;
        },
    ]
];

Yii::$app->grid->bind($attachmentDataProvider, $user_attachment, $grid_option, '', false);
?>