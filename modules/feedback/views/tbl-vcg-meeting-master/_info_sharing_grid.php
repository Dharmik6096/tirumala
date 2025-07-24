<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'info_type_id', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->infoTypeId, 'info_type_desc');
    }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'description', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'month',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->month);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-info-sharing-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($sharingDataProvider, $sharingSearchModel, $grid_option);
    ?>
</div>
