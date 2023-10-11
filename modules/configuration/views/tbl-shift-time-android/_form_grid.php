<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'org_type', 'visible' => true],
    ['attribute' => 'org_code', 'visible' => true],
    [
        'attribute' => 'plant_code',
        'label' => 'PLANT',
        'value' => function ($model) {
            return isset($model->plantCode) ? $model->plantCode['name'] : 'N/A';
        },
        'visible' => true,
    ],
    [
        'attribute' => 'mcc_plant_code',
        'label' => 'MCC',
        'value' => function ($model) {
            if ($model->org_type === 'BMC' && isset($model->mccName)) {
                return $model->mccName['name'];
            } elseif ($model->org_type === 'MCC' && isset($model->mccCode)) {
                return $model->mccCode['name'];
            } else {
                return 'N/A';
            } 
        },
        'visible' => true
    ],
    [
        'attribute' => 'bmc_code',
        'label' => 'BMC',
        'value' => function ($model) {
            return ($model->org_type === 'BMC' && isset($model->bmcCode)) ? $model->bmcCode['bmc_name'] : 'N/A';
        },
        'visible' => true
    ],
    [
        'attribute' => 'org_name',
        'label' => 'Organization Name',
        'value' => function ($model) {
            if ($model->org_type === 'BMC' && isset($model->bmcCode)) {
                return $model->bmcCode->bmc_name;
            } elseif ($model->org_type === 'MCC' && isset($model->mccCode)) {
                return $model->mccCode->name;
            } else {
                return 'N/A';
            }
        },
    ],
    ['attribute' => 'collection_type', 'visible' => true],
    ['attribute' => 'm_start_time', 'filter' => false],
    ['attribute' => 'e_start_time', 'filter' => false],
    ['attribute' => 'm_lock_time', 'filter' => false],
    ['attribute' => 'e_lock_time', 'filter' => false],
    ['attribute' => 'date_shift_enable',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'date_shift_enable'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->date_shift_enable]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->date_shift_enable] : '';
        },],
    ['attribute' => 'grace_hr', 'visible' => true],
];

$grid_option = [
    'id' => 'tbl-shift-time-android',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>