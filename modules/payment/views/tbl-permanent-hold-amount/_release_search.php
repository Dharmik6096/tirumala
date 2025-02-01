<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblMemberPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-release-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'validateOnBlur' => false,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>   
    <div class="col-sm-12 mt10 padding-left-0">
        <div class="col-sm-3" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblpermanentholdamountsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div> 
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblpermanentholdamountsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>      
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblpermanentholdamountsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblpermanentholdamountsearch-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>
        </div>
        
        <div class=" col-sm-2 form-group mt23">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
    <?php
        if (!empty($dataProvider->getModels())) {
            ?>
            <div class="col-sm-2">
                <?php
                $where = json_encode(['data_lock_member' => 1, 'billing_lock_member' => 0, 'productsale_lock_member' => 1]);
                echo Html::hiddenInput('customer_type', 'DCS', ['id' => 'customer_type']);
                echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
                echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
                ?>
                <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblpermanentholdamountsearch-union_code,tblpermanentholdamountsearch-bmc_code,customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
            </div>
            <?php
        }
        ?>
</div>
