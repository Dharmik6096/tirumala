<div class="grid-search">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION')
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
        ['attribute' => 'route_code', 'value' => 'route_code'],
        ['attribute' => 'route_name', 'value' => 'route_name'],
        ['attribute' => 'bmc_code', 'value' => 'tblDcsBmc.bmc_name', 'visible' => true, 'filter' => true],
        ['attribute' => 'local_name'],
        ['attribute' => 'capacity', 'value' => 'capacity'],
        ['attribute' => 'start_time',
        'value' => 'start_time',
        'filter' => yii\widgets\MaskedInput::widget(['model' => $searchModel, 'attribute' => 'start_time', 'mask' => '99:99:99',])],
        ['attribute' => 'return_time',
        'value' => 'return_time',
        'filter' => yii\widgets\MaskedInput::widget(['model' => $searchModel, 'attribute' => 'return_time', 'mask' => '99:99:99',]),],
        ['attribute' => 'route_length_kms', 'value' => 'route_length_kms'],
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'vehicle_type_code', 'value' => 'vehicleType.vehicle_type', 'visible' => false, 'filter' => false],
        ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'route-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'route_name,route_code,tbl-routes/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>