<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? true : false;

$form = ActiveForm::begin([
            'options' => ['id' => 'staff-attendance-form'],
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffattendance-union_code', 'form-group col-sm-3 ' . $class, $model->getAttributeLabel('staff_member_code'), 'staff_member_code', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('lwp_type', $model, $form, 'form-group', $model->getAttributeLabel('lwp_type')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'lwp_date', 'form-group col-sm-3', true, '', false); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remark', ['options' => ['class' => 'form-group']])->textarea() ?>
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

