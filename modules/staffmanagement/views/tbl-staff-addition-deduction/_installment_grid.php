<?php

$attribute = [
    //  ['attribute' => 'staff_installment_code', ],
    ['attribute' => 'amount',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->amount);
        }, 'filter' => FALSE
    ],
    ['attribute' => 'deduction_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->deduction_date);
        }, 'filter' => FALSE],
    ['attribute' => 'salary_processed', 'value' => function($model) {
            return ($model->salary_processed == 1) ? Yii::t('app', 'Processed') : Yii::t('app', 'Unprocessed');
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'installment-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>