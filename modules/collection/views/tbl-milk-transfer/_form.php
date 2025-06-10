<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

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

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('transfer_type', $model, $form, 'form-group', $model->getAttributeLabel('transfer_type'), $readonly, 'transfer_type', false); ?>
    </div>
    <?= Html::hiddenInput('bmc_code', '', ['id' => 'tblmilktransfer-bmc_code']); ?>
    <div class="col-sm-1">
        <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'source_type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilktransfer-source_type,tblmilktransfer-union_code,tblmilktransfer-bmc_code', 'source_code', $model->getAttributeLabel('source_code'), FALSE, $readonly); ?>
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'destination_type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilktransfer-destination_type,tblmilktransfer-union_code,tblmilktransfer-bmc_code', 'destination_code', $model->getAttributeLabel('destination_code'), FALSE, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE, FALSE, $readonly); ?>
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift', true, $readonly, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE, FALSE, $readonly); ?>
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift', true, $readonly, 'to_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_datetime', '', date('d-m-Y'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'vehicle_no')->textInput(['readOnly' => $readonly]) ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'fat')->textInput() ?>
    </div>
    <div class="col-sm-1  number-validate">
        <?= $form->field($model, 'snf')->textInput() ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'temp')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_rechilling', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
