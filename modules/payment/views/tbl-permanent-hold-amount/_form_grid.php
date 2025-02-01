<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
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
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
                
    ['attribute' => 'customer_type', 'label' => Yii::t('app', 'Type')],
                
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code')],
                
    ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }],
    ['attribute' => 'customer_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Name'), 'filter' => false],
    // ['attribute' => 'payment_cycle_code', 'value' => function($model) {
    //         return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
    //     }, 'filter' => false, 'format' => 'raw', 'label' => Yii::t('app', 'Period')],
    ['attribute' => 'bank_code', 'label' => Yii::t('app', 'Bank Code')],
    ['attribute' => 'bank_name', 'label' => Yii::t('app', 'Bank Name')],
    ['attribute' => 'branch_code', 'label' => Yii::t('app', 'Branch Code')],
    ['attribute' => 'branch_name', 'label' => Yii::t('app', 'Branch Name')],
    ['attribute' => 'ifsc', 'label' => Yii::t('app', 'IFSC')],
    ['attribute' => 'bank_account_no', 'label' => Yii::t('app', 'Bank Account No')],
    ['attribute' => 'beneficiary_name', 'label' => Yii::t('app', 'Beneficiary Name')],
    ['attribute' => 'is_verified', 'label' => Yii::t('app', 'Is Verified')],
    [
        'attribute' => 'from_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'label' => Yii::t('app', 'From Date'), 'filter' => false],
    [
        'attribute' => 'to_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'label' => Yii::t('app', 'To Date'), 'filter' => false],
    [
        'attribute' => 'created_at',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->created_at);
        }, 'label' => Yii::t('app', 'Hold Date'), 'filter' => false],
    ['attribute' => 'hold_amount'],
    [
        'attribute' => 'release_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->release_date);
        }, 'label' => Yii::t('app', 'Release Date'), 'filter' => false],
    [
        'attribute' => 'release_by',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->releaseBy, 'name');
        }, 'label' => Yii::t('app', 'Release By'), 'filter' => false],
];

$grid_option = [
    'id' => 'release-member-payment-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>