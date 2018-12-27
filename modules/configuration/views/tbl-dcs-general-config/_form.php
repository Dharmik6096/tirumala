<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblDcsGeneralConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-dcs-general-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'allow_multiple_voters')->textInput() ?>

    <?= $form->field($model, 'backup_path')->textInput() ?>

    <?= $form->field($model, 'backup_per_shift')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'election_alert_day')->textInput() ?>

    <?= $form->field($model, 'election_term')->textInput() ?>

    <?= $form->field($model, 'is_backup_user_choice')->textInput() ?>

    <?= $form->field($model, 'is_backup_on_closing')->textInput() ?>

    <?= $form->field($model, 'is_backup_disbursement')->textInput() ?>

    <?= $form->field($model, 'max_share_buy')->textInput() ?>

    <?= $form->field($model, 'min_share_req')->textInput() ?>

    <?= $form->field($model, 'nos_of_reminders')->textInput() ?>

    <?= $form->field($model, 'purchase_rate_with_tax')->textInput() ?>

    <?= $form->field($model, 'sale_rate_with_tax')->textInput() ?>

    <?= $form->field($model, 'share_issued')->textInput() ?>

    <?= $form->field($model, 'share_unit_cost')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'milk_dispatch_in')->textInput() ?>

    <?= $form->field($model, 'headload_km')->textInput() ?>

    <?= $form->field($model, 'milk_dispatch_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'milk_receipt_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'product_sale_in_cash')->textInput() ?>

    <?= $form->field($model, 'share_amount_editable')->textInput() ?>

    <?= $form->field($model, 'product_billing')->textInput() ?>

    <?= $form->field($model, 'billing_zero_amount_auto')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
