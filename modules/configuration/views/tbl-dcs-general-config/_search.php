<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblDcsGeneralConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-dcs-general-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'allow_multiple_voters') ?>

    <?= $form->field($model, 'backup_path') ?>

    <?= $form->field($model, 'backup_per_shift') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'election_alert_day') ?>

    <?php // echo $form->field($model, 'election_term') ?>

    <?php // echo $form->field($model, 'is_backup_user_choice') ?>

    <?php // echo $form->field($model, 'is_backup_on_closing') ?>

    <?php // echo $form->field($model, 'is_backup_disbursement') ?>

    <?php // echo $form->field($model, 'max_share_buy') ?>

    <?php // echo $form->field($model, 'min_share_req') ?>

    <?php // echo $form->field($model, 'nos_of_reminders') ?>

    <?php // echo $form->field($model, 'purchase_rate_with_tax') ?>

    <?php // echo $form->field($model, 'sale_rate_with_tax') ?>

    <?php // echo $form->field($model, 'share_issued') ?>

    <?php // echo $form->field($model, 'share_unit_cost') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'milk_dispatch_in') ?>

    <?php // echo $form->field($model, 'headload_km') ?>

    <?php // echo $form->field($model, 'milk_dispatch_quantity_mode') ?>

    <?php // echo $form->field($model, 'milk_receipt_quantity_mode') ?>

    <?php // echo $form->field($model, 'product_sale_in_cash') ?>

    <?php // echo $form->field($model, 'share_amount_editable') ?>

    <?php // echo $form->field($model, 'product_billing') ?>

    <?php // echo $form->field($model, 'billing_zero_amount_auto') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
