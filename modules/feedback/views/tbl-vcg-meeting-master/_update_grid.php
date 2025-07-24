<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'description', 'filter' => false],
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
    'id' => 'vcg-update-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($updateDataProvider, $updateSearchModel, $grid_option);
    ?>
</div>
