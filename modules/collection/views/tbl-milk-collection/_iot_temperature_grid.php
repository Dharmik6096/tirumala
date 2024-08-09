<?php

use kartik\grid\GridView;
use yii\helpers\Html;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => TRUE],
    ['label' => Yii::t('app', ' MCC Ref. Code'), 'attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => TRUE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => Yii::t('app', 'BMC Ref. Code'), 'attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => true],
    ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
    ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
    ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => true],
    ['label' => 'Date', 'attribute' => 'date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time);
        }],
    ['attribute' => 'device_id', 'filter' => false],
    ['attribute' => 'temperature', 'filter' => Html::activeTextInput($searchModel, 'temperature', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_temperature', $operator, ['class' => 'form-control'])],
];

$grid_option = [
    'id' => 'iot-temperature-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
