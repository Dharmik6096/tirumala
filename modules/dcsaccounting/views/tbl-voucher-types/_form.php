<?php

use app\components\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);

$readonly = $type == 'create' ? FALSE : TRUE;
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'voucher_type_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('payment_mode_member', $model, $form, '', $model->getAttributeLabel('voucher_type'), false, 'voucher_type'); ?>
    </div>
    <div class="col-sm-2 hide-ledger">
        <?php echo Html::hiddenInput('ledger_type', 'cash', ['id' => 'ledger_type']); ?>
        <?= Yii::$app->dropdown->ledgerList($model, $form, 'tblvouchertypes-union_code,ledger_type', 'ledger_code', $model->getAttributeLabel('ledger_code'), false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('credit_debit', $model, $form, '', $model->getAttributeLabel('credit_debit'), false, 'credit_debit'); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
        $('.hide-ledger').hide(); 
        hideShowLedger();
        
        $('#tblvouchertypes-voucher_type').on('change', function() {
            hideShowLedger();
        });

        function hideShowLedger() {
            var voucherType = $('#tblvouchertypes-voucher_type').val();
            if (voucherType && voucherType.toLowerCase() == 0) {
                $('.hide-ledger').show();
            } else {
                $('.hide-ledger').hide();
                $('#tblvouchertypes-ledger_code').val('').trigger('change');
            }
        }
    });
";
$this->registerJs($script, \yii\web\View::POS_END);
?>