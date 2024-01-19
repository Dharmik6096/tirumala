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
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    [
        'attribute' => 'bmc_code',
        'label' => Yii::t('app', 'Source Type'),
        'value' => function ($model) {
            return !empty($model->bmc_code) ? 'BMC' : 'PLANT';
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    [
        'attribute' => 'bmc_name',
        'label' => Yii::t('app', 'Source Name'),
        'value' => function ($model) {
            $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
            $sourceCode = !empty($model->bmc_code) ? $model->bmc_code : $model->plant_code;
            $rel = Yii::$app->general->getDestRelation($sourceType);
            $att = strtolower($sourceType) == 'bmc' ? 'bmc_name' : (strtolower($sourceType) == 'vendor' ? 'customer_name' : (strtolower($sourceType) == 'party' ? 'party_name' : 'name'));
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->$rel, $att) . '-' . $sourceCode;
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'bmc_code',
        'label' => Yii::t('app', 'Source Code'),
        'value' => function ($model) {
            return !empty($model->bmc_code) ? $model->bmc_code : $model->plant_code;
        },
        'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'bmc_code',
        'label' => (Yii::t('app', 'Source Ref.Code')),
        'value' => function ($model) {
            $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
            $rel = Yii::$app->general->getDestRelation($sourceType);
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->$rel, 'ref_code');
        }, 'vAlign' => 'middle'
    ],
    [
        'attribute' => 'vehicle_code',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }
    ],
    ['attribute' => 'trip_code'],
    [
        'attribute' => 'inspection_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->inspection_date);
        }
    ],
    [
        'attribute' => 'shift_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    ['attribute' => 'remarks'],
];
foreach ($config_list as $config) {
    $attribute[] = [
        'attribute' => 'config_code', 'label' => Yii::t('app', $config->config_name),
        'value' => function ($model) use ($config) {
            $model->config_code = $config->config_code;
            $configResult = $model->configResult;
            return !empty($configResult) ? (!empty($configResult->configResultCode->config_result) ? $configResult->configResultCode->config_result : $configResult->config_result) : '';
        }
    ];
}

$grid_option = [
    'id' => 'inspection-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
