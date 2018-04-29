<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'id'],
    ['attribute' => 'type'],
    ['attribute' => 'title'],
    ['attribute' => 'msg'],
    ['attribute' => 'msg_by'],
    [
    'attribute' => 'publish_date',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->publish_date);
    }],
];

$grid_option = [
    'id' => 'notification-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'title,id,tbl-notifications/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
