<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
    ['attribute' => 'salary_head_code', 'label' => Yii::t('app', 'Salary Head Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->salaryHeadCode, 'salary_head_name');
        }, 'filter' => FALSE],
    ['attribute' => 'value',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->value);
        }, 'filter' => FALSE
    ],
    ['label' => Yii::t('app', 'Type'),
        'value' => function ($model) {
            return ($model->salaryHeadCode->salary_head_type == 1) ? Yii::t('app', 'Addition') : Yii::t('app', 'Deduction');
        },
    ],
    ['attribute' => 'lwp_effect',
        'value' => function ($model) {
            if ($model->salaryHeadCode->salary_head_type == 1) {
                return ($model->lwp_effect == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
            }
            return '';
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'transaction-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>