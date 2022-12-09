<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

//$readonly = $type == 'create' ? FALSE : TRUE;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? true : false;
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

    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>

    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('scheme_id', $model, $form, 'tblschemeapplicationdisbursement-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('scheme_id')); ?>
    </div>

    <div class="col-sm-3 <?= $class ?>">
        <?php
        echo Html::hiddenInput('application_status', 'approved', ['id' => 'application_status']);
        echo Html::hiddenInput('application_id', $model->application_id, ['id' => 'application_id']);
        echo Yii::$app->dropdown->welfareSchemeApplication($model, $form, 'tblschemeapplicationdisbursement-scheme_id,application_status,application_id', 'application_id', $model->getAttributeLabel('application_id'), false, false, $readonly);
        ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'disburse_date', '', false, false, false); ?>
    </div>

    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'disburse_value')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('ws_payment_mode', $model, $form, '', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', FALSE, FALSE, FALSE); ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'party_name')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('relation', $model, $form, '', 'Party Relation', false, 'party_relation'); ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'payment_ref_id')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'payment_detail')->textInput(['maxlength' => true]) ?>   
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textArea(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>  
    </div>
</div>
<?php ActiveForm::end(); ?>
