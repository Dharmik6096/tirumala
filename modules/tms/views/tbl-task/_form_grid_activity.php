<?php

$attribute = [
        [
        'attribute' => 'task_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->task_datetime);
        }],
        ['attribute' => 'route_code',],
        ['attribute' => 'module_type',],
        ['attribute' => 'module_code',],
        ['attribute' => 'status',],
        ['attribute' => 'contact_person',],
        ['attribute' => 'contact_person_mobile_no',],
        ['attribute' => 'activity_datetime',],
        ['attribute' => 'remarks',],
        ['attribute' => 'form_data',],
//                ['attribute' => 'module_type', 'value' => function($model) {
//                    $rel = Yii::$app->general->getDestRelation($model->module_type);
//                    $att = strtolower($model->source_org_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_org_type) == 'vendor' ? 'customer_name' : 'name');
//                    if (!empty($rel))
//                        return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att) . '-' . strtoupper($model->source_org_type);
//                }, 'filter' => false],
];
$grid_option = [
    'id' => 'task-activity-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], FALSE);
?>
