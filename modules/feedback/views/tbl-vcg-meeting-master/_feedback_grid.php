<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'member_code', 'filter' => false],
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
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($feedbackDataProvider, $feedbackSearchModel, $grid_option);
    ?>
</div>
