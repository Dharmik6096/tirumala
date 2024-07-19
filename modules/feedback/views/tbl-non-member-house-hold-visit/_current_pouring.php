<?php
$attribute = [
    ['attribute' => 'competitor_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->competitorCode, 'competitor_name');
        },'filter' => false],
    ['attribute' => 'milk_volume', 'filter' => false],
    ['attribute' => 'milk_rate', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'mpp-survey-competitor-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
