<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$attribute = [
    ['attribute' => 'election_id',
        'filter' => false],
    ['attribute' => 'election_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->election_date);
        },
        'filter' => false],
    ['attribute' => 'tenure_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_from);
        },
        'filter' => false],
    ['attribute' => 'tenure_to',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_to);
        },
        'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'dcs-election-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'edit' => function ($url, $model) {
            $url = ['/organisation/tbl-dcs-election/update', 'dcs_code' => $model->dcs_code, 'id' => $model->election_id];
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>