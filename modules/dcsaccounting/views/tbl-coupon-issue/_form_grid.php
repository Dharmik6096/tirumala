<?php

use app\modules\globalmaster\models\TblAnimalType;
use yii\helpers\Html;
use kartik\grid\GridView;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'DCS Ref. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'consumer_type'],
        ['attribute' => 'consumer_code'],
        ['attribute' => 'amount'],
        ['attribute' => 'issue_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_date($model->issue_date);
        }],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'voucher_code'],
        ['attribute' => 'payment_mode', 'value' => function($model) {
            return isset($model->payment_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->payment_mode] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_mode', $searchModel, 'payment_mode'),],
        ['attribute' => 'bank_code', 'label' => 'Bank', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
        }],
        ['attribute' => 'is_delete', 'value' => function($model) {
            return !empty($model->is_delete) ? Yii::$app->dropdown->getRecords('status')['data'][$model->is_delete] : '';
        }],
];

$grid_option = [
    'id' => 'coupon-issue',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
