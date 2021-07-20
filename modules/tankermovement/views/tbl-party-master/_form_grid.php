<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'party_name'],
    ['attribute' => 'party_contact_no'],
    ['attribute' => 'owner_name'],
    ['attribute' => 'owner_contact_no'],
    ['attribute' => 'owner_email'],
];

$grid_option = [
    'id' => 'party-master-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
