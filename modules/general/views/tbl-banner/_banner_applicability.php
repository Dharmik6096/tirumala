<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'login_type', 'value' => function ($model) {
            return !empty($model->login_type) ? Yii::$app->dropdown->getRecords('login_type')['data'][$model->login_type] : '';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'banner_applicability',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $model, $grid_option, '', false);
?>
