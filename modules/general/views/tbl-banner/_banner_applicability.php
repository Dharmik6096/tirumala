<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'department', 
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->departmentId, 'department');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'banner_applicability',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $model, $grid_option, '', false);
?>
