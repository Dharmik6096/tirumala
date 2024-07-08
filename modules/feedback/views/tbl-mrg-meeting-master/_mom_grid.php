<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'dcs_code', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'concern', 'filter' => false],
    ['attribute' => 'discussions', 'filter' => false],
    ['attribute' => 'month',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->month);
        }, 'filter' => false],
    ['attribute' => 'process_type', 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'remarks_actions', 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-mom-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($momDataProvider, $momSearchModel, $grid_option);
    ?>
</div>
