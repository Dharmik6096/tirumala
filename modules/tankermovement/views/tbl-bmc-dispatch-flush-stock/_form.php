<?php

use yii\helpers\Html;
use app\components\ActiveForm;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$disabled = ($model->bmc_code != '') ? TRUE : FALSE;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row ">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcdispatchflushstock-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $disabled); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcdispatchflushstock-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $disabled); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcdispatchflushstock-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE, '', '', $disabled); ?>
    </div>  
    <div class="col-sm-2 ">
        <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmcdispatchflushstock-module_name']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $model, $form, 'tblbmcdispatchflushstock-bmc_code,tblbmcdispatchflushstock-module_name', 'form-group col-sm-4', $model->getAttributeLabel('bmc_silos_info_code'), '', $readonly, '', '', FALSE, '', TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, $disabled, true); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $disabled, 'shift_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-4"> 
        <?= $form->field($model, 'remarks')->textInput() ?>
    </div>
    <div class="clearfix"></div>

</div>
<div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>