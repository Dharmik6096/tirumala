<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
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
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcsdeactive-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, ''); ?>  
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcsdeactive-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, ''); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcsdeactive-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', ''); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldcsdeactive-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, ''); ?>         
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-3', false, '', false); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
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
