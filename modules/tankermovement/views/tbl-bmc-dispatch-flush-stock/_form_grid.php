<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
        ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
        [
        'attribute' => 'transaction_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }, 'filter' => false],
        ['attribute' => 'shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'filter' => false],
        ['attribute' => 'milk_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('milk_quality_type_code', $searchModel, 'milk_quality_type_code', Yii::t('app', 'Select'))],
        ['attribute' => 'bmc_silos_info_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->silosInfoCode, 'silo_no');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'qty'],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'tbl-bmc-dispatch-flush-stock-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/tankermovement/tbl-bmc-dispatch-flush-stock/update', 'id' => $model->bmc_dispatch_flush_stock_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
