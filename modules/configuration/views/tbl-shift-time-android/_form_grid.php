<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'code', 'visible' => true],
    ['attribute' => 'org_type', 'visible' => true],
    [
        'attribute' => 'org_code',
        'value' => function ($model) {
            if ($model->org_type === 'BMC') {
                return $model->bmcCode->bmc_name;
            } elseif ($model->org_type === 'MCC') {
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
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('date_shift_enable', $searchModel, 'date_shift_enable'),
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