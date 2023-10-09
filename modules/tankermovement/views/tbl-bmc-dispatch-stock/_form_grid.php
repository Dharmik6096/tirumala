<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
        ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Code')), 'value' => 'bmc_code', 'vAlign' => 'middle', 'filter' => false],
        [
        'attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }],
        [
        'attribute' => 'from_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
        ['attribute' => 'from_shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift');
        }],
        [
        'attribute' => 'to_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => false],
        ['attribute' => 'to_shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShiftCode, 'shift');
        }, 'filter' => false],
        ['attribute' => 'milk_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'qty_diff_type_code', 'label' => 'Qty Diff Type',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->qtyDiffType, 'qty_diff_type_name');
        }, 'filter' => false],
        ['attribute' => 'opening_bal'],
        ['attribute' => 'purchase_qty'],
        ['attribute' => 'qty_diff', 'filter' => false],
        ['attribute' => 'balance_qty'],
        ['attribute' => 'fat'],
        ['attribute' => 'snf'],
        ['attribute' => 'type'],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'tbl-bmc-dispatch-stock-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
