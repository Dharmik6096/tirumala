<?php
/*

  use yii\helpers\Html;
  use yii\widgets\ActiveForm;

  /* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductRequisitionTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
/*
  ?>

  <div class="tbl-product-requisition-transaction-search">

  <?php $form = ActiveForm::begin([
  'action' => ['accept-requisition'],
  'method' => 'get',
  ]); ?>
  <div class="col-sm-2">
  <?= $form->field($model, 'requisition_transaction_code') ?>
  </div>
  <div class="col-sm-2">
  <?php $model->from_date = !empty($model->from_date) ? $model->from_date : date('d-m-Y'); ?>
  <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, true); ?>
  </div>
  <div class="col-sm-2">
  <?php $model->to_date = !empty($model->to_date) ? $model->to_date : date('d-m-Y'); ?>
  <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, true); ?>
  </div>
  <!--    <div class="col-sm-2">
  <?php // $form->field($model, 'product_requisition_code') ?>
  </div>
  <div class="col-sm-2">
  <? $form->field($model, 'quantity') ?>
  </div>
  <div class="col-sm-2">
  <? $form->field($model, 'provisional_rate') ?>
  </div>
  <div class="col-sm-2">
  <? $form->field($model, 'provisional_amount') ?>
  </div>-->
  <?php // echo $form->field($model, 'discount_amount') ?>

  <?php // echo $form->field($model, 'product_code') ?>

  <?php // echo $form->field($model, 'status') ?>

  <?php // echo $form->field($model, 'is_approved') ?>

  <?php // echo $form->field($model, 'approved_by') ?>

  <?php // echo $form->field($model, 'approved_quantity') ?>

  <?php // echo $form->field($model, 'approved_date') ?>

  <?php // echo $form->field($model, 'created_at') ?>

  <?php // echo $form->field($model, 'created_by') ?>

  <?php // echo $form->field($model, 'updated_at') ?>

  <?php // echo $form->field($model, 'updated_by') ?>

  <?php // echo $form->field($model, 'originating_org_code') ?>

  <?php // echo $form->field($model, 'originating_org_type') ?>

  <?php // echo $form->field($model, 'originating_type') ?>

  <?php // echo $form->field($model, 'x_col1') ?>

  <?php // echo $form->field($model, 'x_col2') ?>

  <?php // echo $form->field($model, 'x_col3') ?>

  <?php // echo $form->field($model, 'x_col4') ?>

  <?php // echo $form->field($model, 'x_col5') ?>
  <div class="col-sm-2">
  <label class="control-label"></label>
  <div class="form-group">
  <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
  <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
  </div>
  </div>

  <?php ActiveForm::end(); ?>

  </div>
 */
?>


<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
            'options' => [
                'field-class' => 'form-group col-sm-2 padding-right-5 padding-left-5',
                'federation_code' => 'form-group col-sm-2 padding-right-5'
            ],
            'action' => ['accept-requisition'],
            'method' => 'get',
        ]);
?>
<div class="row">
    <div class="col-sm-2 height_65" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <!--    <div class="col-sm-2 height_65">
    <?php //Yii::$app->dropdown->union_plant($model, $form, 'tblproductrequisitiontransactionsearch-union_code', 'plant_code', $model->getAttributeLabel('plant'));  ?>
        </div> 
        <div class="col-sm-2">
    <?php // echo Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductrequisitiontransactionsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant'));  ?>
        </div>
        <div class="col-sm-2 height_65">
    <?php // Yii::$app->dropdown->dropdownStatic('requisition_type', $model, $form, 'form-group', $model->getAttributeLabel('vendor_type'), false, 'vendor_type', false);  ?>
        </div>
        <div class="col-sm-2 height_65">
    <?php // Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductrequisitiontransactionsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc'));  ?>
        </div>
    
        <div class="col-sm-2 height_65">
    <?php //echo Yii::$app->dropdown->all_routes($model, $form, 'tblproductrequisition-plant_code,tblproductrequisition-mcc_plant_code,tblproductrequisition-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE);  ?>
        </div>
        <div class="col-sm-2 height_65">
    <?php //echo Yii::$app->dropdown->route_dcs($model, $form, 'tblproductrequisitiontransactionsearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false);  ?>
    <?php //Yii::$app->dropdown->bmc_society($model, $form, 'tblproductrequisitiontransactionsearch-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs'));  ?>
        </div>-->
    <div class="col-sm-2 height_65">
        <?= Yii::$app->dropdown->dropdown('dispatch_center', $model, $form, 'form-group col-sm-2 padding-right-5', $model->getAttributeLabel('dispatch_center')); ?> 
    </div>
    <div class="col-sm-2 height_65">
        <?php $model->from_date = !empty($model->from_date) ? $model->from_date : date('d-m-Y'); ?>
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, true); ?>
    </div>
    <div class="col-sm-2 height_65">
        <?php $model->to_date = !empty($model->to_date) ? $model->to_date : date('d-m-Y'); ?>
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', true, false, false, true); ?>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-2 mt0 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
