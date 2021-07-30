<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
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
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberdeactive-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, ''); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberdeactive-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberdeactive-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmemberdeactive-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, ''); ?>         
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmemberdeactive-dcs_code', '', Yii::t('app', 'Member')); ?>
    </div>
      <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE, date('d-m-Y')); ?> 
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
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
