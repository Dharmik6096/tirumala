<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTempSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-search search-filter large-search">

    <?php
    $checkAmountBmcApprove = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'check_bmc_amount_while_approve', 'PORTAL');
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcollectiondataaliassearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcollectiondataaliassearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcollectiondataaliassearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <?php if (!isset($showType)) { ?>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblcollectiondataaliassearch-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
        </div>
    <?php } ?>
    <?php if (isset($showFarmer) && $showFarmer) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblcollectiondataaliassearch-dcs_code', '', Yii::t('app', 'Member')); ?>
        </div>
    <?php } ?>
    <?php if (isset($showType) && $showType) { ?>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblcollectiondataaliassearch-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
        </div>  
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->customer_code($model, $form, 'tblcollectiondataaliassearch-bmc_code,tblcollectiondataaliassearch-customer_type', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE); ?>
        </div>  
    <?php } ?>
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-2 mb-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift mb-2">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('action_perform', $model, $form, 'form-group', $model->getAttributeLabel('action_perform'), false, 'action_perform'); ?>
    </div>
    <?php 
    if (isset($showGroupBy) && $showGroupBy && $checkAmountBmcApprove) { ?>
        <div class="col-sm-2 hide_section">
            <?= Yii::$app->dropdown->dropdownStatic('indent_group_by', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Group By'), false, 'group_by') ?>
        </div>
    <?php } ?>
    <div class="col-sm-2 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

