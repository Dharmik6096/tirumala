<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$vendor = $batchNoWiseInventory == 1 ? FALSE : TRUE;
$plant = $batchNoWiseInventory == 1 ? TRUE : FALSE;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => $plant, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'bmc_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'vendor_master_code','label' => Yii::t('app', 'Vendor Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vendorCode, 'vendor_code');
        }, 'visible' => $vendor, 'filter' => false],
        ['attribute' => 'vendor_master_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vendorCode, 'vendor_name');
        }, 'visible' => $vendor,],
        ['attribute' => 'grn_no'],
        ['label' => Yii::t('app', 'GRN Date'), 'attribute' => 'grn_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->grn_date);
        }],
        ['attribute' => 'invoice_no'],
        ['attribute' => 'invoice_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->invoice_date);
        }],
        ['attribute' => 'payment_mode',
        'value' => function($model) {
            return ($model->payment_mode == 1) ? 'Yes' : 'No';
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'deduction_start_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->deduction_start_date);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'no_of_installment', 'visible' => false, 'filter' => false],
        ['attribute' => 'amount', 'visible' => true, 'filter' => false],
        ['attribute' => 'is_stock_posted', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_stock_posted');
        }, 'visible' => TRUE, 'filter' => false],
        ['label' => Yii::t('app', 'Created date'), 'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->created_at);
        }],
        [
        'attribute' => 'created_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
