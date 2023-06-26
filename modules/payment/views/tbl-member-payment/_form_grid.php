<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
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
        'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
        ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }],
        ['attribute' => 'dcs_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS')],
        ['attribute' => 'payment_cycle_code', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime);
        }, 'filter' => false, 'format' => 'raw'],
        [
        'attribute' => 'payment_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->payment_date);
        }],
        ['attribute' => 'member_count', 'filter' => false, 'visible' => false],
        ['attribute' => 'kg_fat'],
        ['attribute' => 'kg_snf'],
        ['attribute' => 'qty'],
        ['attribute' => 'avg_fat', 'visible' => false],
        ['attribute' => 'avg_snf', 'visible' => false],
        ['attribute' => 'avg_rate', 'visible' => false],
        ['attribute' => 'total_amount'],
        ['attribute' => 'total_addition'],
        ['attribute' => 'total_deduction'],
        ['attribute' => 'previous_hold'],
        ['attribute' => 'previous_due'],
        ['attribute' => 'net_payable'],
        ['attribute' => 'hold_amount'],
        ['attribute' => 'additional_pay'],
        ['attribute' => 'final_amount'],
        ['attribute' => 'payment_status'],
];

$grid_option = [
    'id' => 'member-payment-export-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'detail-view' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View'), 'class' => ''];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/payment/tbl-member-payment/view', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'payment_status' => $model->payment_status], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

