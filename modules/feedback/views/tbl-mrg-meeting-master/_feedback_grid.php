<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'member_code', 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
    }, 'label' => Yii::t('app', 'Ex Member Code'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'feedback_desc', 'filter' => false],
    ['attribute' => 'action_taken', 'filter' => false],
    ['attribute' => 'type', 'filter' => false],
    ['attribute' => 'month',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->month);
        }, 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-feedback-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-val' => $model->MRG_M_feedback_id];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/feedback/tbl-mrg-meeting-feedback/update', 'id' => $model->MRG_M_feedback_id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($feedbackDataProvider, $feedbackSearchModel, $grid_option);
    ?>
</div>
