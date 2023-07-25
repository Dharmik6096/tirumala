<?php

use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }],
        ['attribute' => 'module_type',],
        ['attribute' => 'module_code',],
        ['attribute' => 'module_code',
        'label' => Yii::t('app', 'Ref.Code'),
        'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->module_type);
            return Yii::$app->general->getforeignkey($model->{$rel}, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'module_code',
        'label' => Yii::t('app', 'Name'),
        'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->module_type);
            $att = strtolower($model->module_type) == 'bmc' ? 'bmc_name' : (strtolower($model->module_type) == 'dcs' ? 'dcs_name' : 'name');
            if (!empty($rel)) {
                return Yii::$app->general->getforeignkey($model->{$rel}, $att);
            }
        }, 'filter' => false],
        ['attribute' => 'status',],
        ['attribute' => 'contact_person',],
        ['attribute' => 'contact_person_mobile_no',],
        [
        'attribute' => 'activity_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->activity_datetime);
        }],
        ['attribute' => 'remarks',],
];
$grid_option = [
    'id' => 'task-activity-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view-form' => function ($url, $model) {
            if (!empty($model->form_data)) {
                $options = ['target' => '_blank', 'title' => Yii::t('app', 'View Form'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View Form')];
                return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/tms/tbl-task/view-form', 'id' => $model->task_activity_code], $options);
            }
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], FALSE);
?>
