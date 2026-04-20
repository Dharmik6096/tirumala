<?php

use app\modules\globalmaster\models\TblAnimalType;
use yii\helpers\Html;

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
        ['label' => Yii::t('app', 'DCS') . ' Ref. Code', 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'consumer_type', 'value' => function($model) {
            return isset($model->consumer_type) ? Yii::$app->dropdown->getRecords('consumer_type')['data'][$model->consumer_type] : '';
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('consumer_type', $searchModel, 'consumer_type'),],
        ['attribute' => 'consumer_code', 'label' => Yii::t('app', 'Consumer Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->consumer_type, FALSE, FALSE, FALSE, FALSE, TRUE);
        }],
        ['attribute' => 'balance'],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
];

$grid_option = [
    'id' => 'coupon-balance',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
