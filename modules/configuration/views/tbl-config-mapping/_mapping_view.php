<?php ?>
<?php

$attribute = [
    ['attribute' => 'config_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->configCode, 'config_name');
        },
        'filter' => false],
    ['attribute' => 'config_code', 'label' => Yii::t('app', 'Is Adultration'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->configCode, 'is_adultration') == 1 ? 'Yes' : 'No';
        },
        'filter' => false],
    ['attribute' => 'config_code', 'label' => Yii::t('app', 'Expected Value'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->configCode, 'check_value');
        },
        'filter' => false],
    ['attribute' => 'config_result', 'filter' => false, 'visible' => Yii::$app->general->getforeignkey($model->configCode, 'is_input_config') == 1 ? TRUE : FALSE]
];
$grid_option = [
    'id' => 'config-mapping-view-list',
    'attributes' => $attribute,
    'active_column' => false,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
