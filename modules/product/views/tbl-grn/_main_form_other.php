<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'grn-form-other'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'GRN') ?></h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= $form->field($model, 'grn_no')->textInput(['readonly' => TRUE]) ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'grn_date', '', TRUE, date('Y-m-d'), TRUE, true); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblgrn-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblgrn-union_code', 'mcc_plant_code', Yii::t('app', 'Own MCC')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?php echo Html::hiddenInput('status', 0, ['id' => 'status']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('ref_no', $model, $form, 'tblgrn-plant_code,tblgrn-mcc_plant_code,status', 'form-group col-sm-4', $model->getAttributeLabel('ref_no'), 'ref_no', FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields disable_div">
            <?= Yii::$app->controls->date($model, $form, 'invoice_date', '', false, false, false, true); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= $form->field($model, 'invoice_no')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="col-sm-4 create_fields">
            <?= $form->field($model, 'remarks')->textInput() ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
</div>

<?php ActiveForm::end(); ?>

