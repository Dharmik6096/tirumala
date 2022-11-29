<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'module_code', 'filter' => false],
    ['attribute' => 'module_name', 'filter' => false],
    ['attribute' => 'error_description', 'filter' => false],
];

$grid_option = [
    'id' => 'sms-fail-log-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
