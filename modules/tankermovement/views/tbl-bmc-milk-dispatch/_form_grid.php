<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
    'challan_no',
    'transaction_date',
    'from_date',
    'from_shift_code',
    'to_date',
    'to_shift_code',
    'destination_type',
    'destination_code',
    'vehicle_code',
    'trip_code',
    'driver_name',
    'driver_contact_no',
    'authorizer_name',
    'vehicle_in_time',
    'vehicle_out_time',
    'remarks',
    'gross_weight',
    'tare_weight',
    'is_last_destination',
    'purchase_rate_code',
    'union_code',
    'plant_code',
    'mcc_plant_code',
    'bmc_code'
];

$grid_option = [
    'id' => 'bmc-milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
