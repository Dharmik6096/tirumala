<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['confirm-payment']);
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
        ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS')],
        ['attribute' => 'member_count', 'filter' => false, 'visible' => false],
        ['attribute' => 'qty'],
        ['attribute' => 'avg_fat'],
        ['attribute' => 'avg_snf'],
        ['attribute' => 'kg_fat', 'visible' => false],
        ['attribute' => 'kg_snf', 'visible' => false],
        ['attribute' => 'avg_rate'],
        ['attribute' => 'total_amount', 'value' => 'total_amount',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => Yii::$app->general->CurrencyFormat(),
    ],
        ['attribute' => 'total_deduction', 'value' => 'total_deduction',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => Yii::$app->general->CurrencyFormat(),
    ],
        ['attribute' => 'final_amount', 'value' => 'final_amount',
        'hAlign' => Yii::$app->general->ColoumnAlign(),
        'format' => Yii::$app->general->CurrencyFormat(),
    ],
        ['attribute' => 'payment_status'],
];

$grid_option = [
    'id' => 'member-payment-export-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

