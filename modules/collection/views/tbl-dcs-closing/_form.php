<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcsclosing-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcsclosing-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcsclosing-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldcsclosing-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, ''); ?>         
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date',date('d-m-Y')); ?> 
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', date('d-m-Y')); ?> 
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift_code', true, false, 'to_shift_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', FALSE); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'fat')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'snf')->textInput() ?>
    </div>
    <div class="col-sm-1 rtpl_validate ">
        <?= $form->field($model, 'water')->textInput() ?>
    </div>
    <div class="col-sm-1 rtpl_validate ">
        <?= $form->field($model, 'protein')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea(['maxlength' => true]) ?>
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
