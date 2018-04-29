

<!--<div class="grid-search">
<?php
//hide state dropdown
//echo $this->render('_search', ['model' => $searchModel]); 
?>
</div>-->

<?php
$attribute = [
    ['attribute' => 'district_code'],
    ['attribute' => 'district_name'],
    ['attribute' => 'local_name'],
];

$grid_option = [
    'id' => 'district-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'district_name,district_code,tbl-districts/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>