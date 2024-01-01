<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'login_type', 'filter' => false],
];

$grid_option = [
    'id' => 'banner_applicability',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $model, $grid_option, '', false);
?>
