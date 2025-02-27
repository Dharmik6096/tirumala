<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'release-member-payment-form',
            'method' => 'post',
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
// echo Html::hiddenInput('payment_cycle_code', 'payment_cycle_code', ['id' => 'payment_cycle_code']);
echo Html::hiddenInput('release_date', '', ['id' => 'release_date']);
$attribute = [
    ['class' => 'kartik\grid\CheckboxColumn',
        'rowSelectedClass' => GridView::TYPE_SUCCESS,
        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
        'checkboxOptions' => function ($model) {
            return ['value' => $model['permanent_hold_amount_code']];
        }],
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
        'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => false],
    ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }],
    ['attribute' => 'dcs_name', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS'), 'filter' => false],
    ['attribute' => 'customer_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code');
        }, 'label' => Yii::t('app', 'Member Code Ex'), 'filter' => false],
    ['attribute' => 'customer_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Member Name'), 'filter' => false],
    ['attribute' => 'actual_hold_amount', 'filter' => false],
    ['attribute' => 'hold_amount', 'contentOptions' => ['class' => 'hold-amount'], 'filter' => false],
    ['attribute' => 'release_amount',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rtpl_validate\'>' . $form->field($model, '[' . $model->permanent_hold_amount_code . ']release_amount')->textInput(['value' => 0, 'class' => 'form-control number-validate release-amount',])->label(FALSE) . '</span>';
            }, 'filter' => false
        ],
    ['attribute' => 'hold_amount', 'contentOptions' => ['class' => 'pending-hold-amount'], 'filter' => false, 'label' => 'Pending Hold Amount', 'filter' => false],
    
    // ['attribute' => 'payment_cycle_code', 'value' => function($model) {
    //         return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
    //     }, 'filter' => false, 'format' => 'raw'],
    ['attribute' => 'bank_code', 'label' => Yii::t('app', 'Bank Code'), 'filter' => false],
    ['attribute' => 'bank_name', 'label' => Yii::t('app', 'Bank Name'), 'filter' => false],
    ['attribute' => 'branch_code', 'label' => Yii::t('app', 'Branch Code'), 'filter' => false],
    ['attribute' => 'branch_name', 'label' => Yii::t('app', 'Branch Name'), 'filter' => false],
    ['attribute' => 'ifsc', 'label' => Yii::t('app', 'IFSC'), 'filter' => false],
    ['attribute' => 'bank_account_no', 'label' => Yii::t('app', 'Bank Account No'), 'filter' => false],
    ['attribute' => 'beneficiary_name', 'label' => Yii::t('app', 'Beneficiary Name'), 'filter' => false],
    ['attribute' => 'is_verified', 'label' => Yii::t('app', 'Is Verified'), 'filter' => false],
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
];

$grid_option = [
    'id' => 'release-member-payment-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
if (!empty($dataProvider->getModels())) {
    echo Html::button(Yii::t('app', 'Release'), ['class' => 'btn btn-primary ', 'id' => 'release']);
    echo Yii::$app->controls->custombutton('Cancel', 'create-payment');
} ?>
<?php ActiveForm::end(); ?>