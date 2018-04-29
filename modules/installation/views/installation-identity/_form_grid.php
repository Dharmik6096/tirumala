<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;

$attribute = [
    ['attribute' => 'organization_type',  'vAlign' => 'middle'],
    ['attribute' => 'organization_code', 'vAlign' => 'middle'],
    ['attribute' => 'db_path', 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'installation-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'download' => function ($url, $model) {
                $options = [ 'title'=>'Download','data-pjax'=>0];
                return Html::a('<span class="glyphicon glyphicon-download"></span>', ['/installation/installation-identity/download', 'id' => $model->id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
