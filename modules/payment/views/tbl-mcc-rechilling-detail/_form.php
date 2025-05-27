<?php

use app\components\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;

$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccrechillingdetail-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmccrechillingdetail-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmccrechillingdetail-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('bmc_chiller_info', $model, $form, 'tblmccrechillingdetail-bmc_code', 'form-group col-sm-2', $model->getAttributeLabel('chiller_info_code'), '', $readonly); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'rate')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'amount')->textInput(['readOnly' => TRUE]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'chilling_date', '',  date('d-m-Y')); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, false, 'shift_code'); ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $(document).ready(function() {
        function calculateAmount() {
            var qty = parseFloat($('#tblmccrechillingdetail-qty').val()) || 0;
            var rt = parseFloat($('#tblmccrechillingdetail-rate').val()) || 0;
            var amt = qty * rt;
            $('#tblmccrechillingdetail-amount').val(amt.toFixed(2));
        }
        $('#tblmccrechillingdetail-qty, #tblmccrechillingdetail-rate').on('change keyup', function() {
            calculateAmount();
        });
    });";
$this->registerJs($script, View::POS_END, 'mcc-rechilling-detail');
?>