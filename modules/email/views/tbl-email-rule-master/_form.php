<?php

use yii\bootstrap5\ActiveForm;

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
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('rule_id', $model, $form, '', 'Rule'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('frequency_data', $model, $form, 'form-group', $model->getAttributeLabel('frequency'), false, 'frequency', false); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'interval')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'no_of_email')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'mobile')->textInput(['class' => 'form-control check_mobile_length']) ?>
        </div>
        <div class="col-sm-12">
        <div class="col-sm-2">
            <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'email_subject')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'email_body')->textarea(['rows' => 6]) ?>
        </div>
    
        <div class="col-sm-2 mt10">
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

