<?php
$attribute = [
    ['attribute' => 'name', 'filter' => false],
    ['attribute' => 'mobile_no', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'mpp-survey-competitor-sahayak-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
