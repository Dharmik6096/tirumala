<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'org_type', 'visible' => true],
        ['attribute' => 'org_code', 'visible' => true],
        ['attribute' => 'standard_time', 'filter' => false],
        ['attribute' => 'exceed_time', 'filter' => false],
        ['attribute' => 'remarks', 'filter' => false],
        ['attribute' => 'status', 'filter' => false],
        ['attribute' => 'status_datetime', 'filter' => false],
        ['attribute' => 'status_by', 'filter' => false],
        ['attribute' => 'status_remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-shift-time-android',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>