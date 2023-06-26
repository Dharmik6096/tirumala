<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<div class="view-subtitle padding_10_0 theme-box ">
<h5 class="theme-box-heading"><?= Yii::t('app', 'Incentive Deduction') ?></h5>

<?php
$attribute = [
    ['attribute' => 'incentive_deduction_id', 'filter' => false,],
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => FALSE],
    ['attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => FALSE],
    ['attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => FALSE],
    ['attribute' => 'scheme_type',
        'filter' => FALSE, 'value' => function($model) {
            return (Yii::$app->dropdown->getRecords('calc_type')['data'][$model->scheme_type] != '') ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->scheme_type] : '';
        },],
    ['attribute' => 'from_time', 'filter' => false],
    ['attribute' => 'to_time', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];

$grid_option = [
    'id' => 'route-detail-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->incentive_deduction_id, 'data-name' => $model->incentive_deduction_id, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil-alt"></i>', ['/organisation/tbl-dpu-incentive/update-detail'], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
</div>