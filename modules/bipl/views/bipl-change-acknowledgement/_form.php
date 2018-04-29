<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bipl\models\BiplChangeAcknowledgement */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
    $disable = '';
    $type = 'create';
} else {
    $disable = 'disabled';
    $type = 'edit';
}

$summary_model=$type=='create'?[$model]:$model;

?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<div class="row"> 
    <div class="col-sm-3">
        <?= $form->field($model, 'svc')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'usr')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pswd')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'cp')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'imei')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'mcc')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'cp_code')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'census_code')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'vendor_id')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'date'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>
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