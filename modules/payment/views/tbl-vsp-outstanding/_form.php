<?php

use yii\bootstrap\ActiveForm;

$readonly = $type == 'create' ? FALSE : TRUE;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvspoutstanding-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvspoutstanding-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvspoutstanding-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvspoutstanding-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_code($model, $form, 'tblvspoutstanding-bmc_code,tblvspoutstanding-customer_type', 'customer_code', Yii::t('app', 'Name'), FALSE, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', TRUE, FALSE, FALSE, $model->getAttributeLabel('transaction_date')); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'hold_amount')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'due_amount')->textInput() ?>
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
