<?php

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Email Rule Master');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
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
<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('rule_id', $model, $form, '', 'Rule'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('frequency_data', $model, $form, 'form-group', $model->getAttributeLabel('frequency'), false, 'frequency', false); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'interval')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'no_of_email')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'mobile')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'email_subject')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'email_body')->textarea(['rows' => 6]) ?>
    </div>
    
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

