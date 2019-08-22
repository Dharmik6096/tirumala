<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['mccPlantCode'], 'name');
        }, 'filter' => false],
            ['attribute' => 'dcs_code', 'filter' => false],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }, 'filter' => false],
            ['attribute' => 'm_start_time'],
            ['attribute' => 'e_start_time'],
            ['attribute' => 'm_cutoff_time',],
            ['attribute' => 'e_cutoff_time'],
            ['attribute' => 'm_lock_time'],
            ['attribute' => 'e_lock_time'],
            ['attribute' => 'inc_rate'],
            ['attribute' => 'inc_deduction'],
        ];

        $grid_option = [
            'id' => 'dpu-incentive-list',
            'attributes' => $attribute,
            'active_column' => false,
            'actions' => [
//        'view' => true,
                'update' => true,
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
