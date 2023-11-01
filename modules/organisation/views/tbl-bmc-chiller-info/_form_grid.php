<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php
$attribute = [
    ['attribute' => 'owner_name', 'filter' => FALSE],
    [
        'attribute' => 'rate_type',
        'filter' => FALSE,
        'value' => function ($model) {
            return isset($model->rate_type) ? Yii::$app->dropdown->getRecords('chiller_rate_type')['data'][$model->rate_type] : '';
        },
    ],
    ['attribute' => 'chilling_capacity', 'filter' => FALSE],
    ['attribute' => 'min_qty', 'filter' => FALSE],
    ['attribute' => 'pan_no', 'filter' => FALSE],
    ['attribute' => 'tds_percentage', 'filter' => FALSE],
    [
        'attribute' => 'installation_date',
        'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->installation_date);
        },
    ],
    ['attribute' => 'agreement_no', 'filter' => FALSE],
    [
        'attribute' => 'agreement_from_date',
        'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->agreement_from_date);
        },
    ],
    [
        'attribute' => 'agreement_to_date',
        'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->agreement_to_date);
        },
    ],
    [
        'attribute' => 'is_active',
        'filter' => FALSE,
        'value' => function ($model) {
            return isset($model->is_active) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_active] : '';
        },
    ],
];

$grid_option = [
    'id' => 'bmc-chiller-info',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->chiller_info_code, 'data-name' => $model->chiller_info_code, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/organisation/tbl-dcs-bmc/update-chiller-info'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
