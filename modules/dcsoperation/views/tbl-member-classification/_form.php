<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMemberClassification */
/* @var $form yii\widgets\ActiveForm */
$type_list = ['animal' => 'Animal', 'land' => 'Land'];
if(Yii::$app->session->get('Unions') != ''){
    $selected = Yii::$app->session->get('Unions');
    $model->union_code = !empty($selected) ? $selected : $model->union_code = !empty($selected);
}
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
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
        <?= $form->field($model, 'member_classification_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'range_from')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'range_to')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'member_classification_type')->dropDownList($type_list, ['prompt' => 'Select Type'])->label(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
