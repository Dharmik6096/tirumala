<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use app\modules\tankermovement\models\TblConfigTxnResult;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'vehicle_code',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }],
    ['attribute' => 'trip_code'],
    [
        'attribute' => 'inspection_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->inspection_date);
}],
    ['attribute' => 'shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'remarks'],
];
foreach ($config_list as $config) {
    $attribute[] = ['attribute' => 'config_code', 'label' => Yii::t('app', $config->config_name),
        'value' => function($model) use ($config) {
            $model->config_code = $config->config_code;
            $configResult = $model->configResult;
            return isset($configResult->configResultCode->config_result) ? $configResult->configResultCode->config_result : $configResult->config_result;
        }];
}

$grid_option = [
    'id' => 'inspection-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
