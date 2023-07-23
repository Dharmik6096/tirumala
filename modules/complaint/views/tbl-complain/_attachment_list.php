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
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($attachmentDataProvider, $complain_attachment, $grid_option, '', false);
?>