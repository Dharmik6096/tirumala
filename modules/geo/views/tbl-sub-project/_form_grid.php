<?php

use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'sub_project_code', 'visible' => true, 'filter' => false],
    ['attribute' => 'sub_project_name', 'visible' => true, 'filter' => true],
    ['attribute' => 'project_code', 'label' => Yii::t('app', 'Project Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->projectCode, 'project_name');
        }, 'visible' => true, 'filter' => true],
    ['attribute' => 'description', 'visible' => true, 'filter' => true],
];

$grid_option = [
    'id' => 'sub-project-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'applicabilty' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/geo/tbl-sub-project/sub-project-applicability', 'id' => $model->sub_project_code], $options);
        },
        'delete' => ['option' => 'sub_project_name,sub_project_code,tbl-sub-project/delete,subProjectDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>