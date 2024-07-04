<?php
$attribute = [
    ['attribute' => 'name', 'filter' => false],
    ['attribute' => 'mobile_no', 'filter' => false],
    ['attribute' => 'milch_animal_cow_cnt', 'filter' => false],
    ['attribute' => 'milch_animal_buff_cnt', 'filter' => false],
    ['attribute' => 'milch_animal_country_cow_cnt', 'filter' => false],
    ['attribute' => 'cow_milk_volume', 'filter' => false],
    ['attribute' => 'buff_milk_volume', 'filter' => false],
    ['attribute' => 'total_milk_volume', 'filter' => false],
    ['attribute' => 'own_milk_consumption', 'filter' => false],
    ['attribute' => 'balance_milk', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'mpp-survey-competitor-membert-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
