<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => FALSE, 'filter' => FALSE],
        ['attribute' => 'process_name', 'filter' => FALSE],
        ['attribute' => 'org_type'],
        ['attribute' => 'org_code', 'value' => function($model) {
            if (($model->process_name) == 'BMC_MILK_DISPATCH') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            } else {
                return Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
            }
        }, 'filter' => false],
        ['attribute' => 'org_name', 'value' => function($model) {
            if (($model->process_name) == 'BMC_MILK_DISPATCH') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } else {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }
        }, 'filter' => false],
        ['attribute' => 'animal_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->animalTypeCode, 'animal_type_name');
        }],
        ['attribute' => 'min_fat'],
        ['attribute' => 'max_fat'],
        ['attribute' => 'min_snf'],
        ['attribute' => 'max_snf'],
        ['attribute' => 'min_clr'],
        ['attribute' => 'max_clr'],
];

$grid_option = [
    'id' => 'milk-quality-param-range-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
