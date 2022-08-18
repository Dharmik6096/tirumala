<?php

use yii\bootstrap\ActiveForm;

$readonly = $type == 'create' ? FALSE : TRUE;
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
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcostparam-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcostparam-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $readonly); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'chilling_rate')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">  
        <?= $form->field($model, 'primary_tpt_cost')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">  
        <?= $form->field($model, 'commission_percentage')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">  
        <?= $form->field($model, 'labour_charge')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate"> 
        <?= $form->field($model, 'service_charge')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'headload_charge')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'building_rent_labour_charge')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'dgset_service_other_charge')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'other_charge_addition')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'other_charge_deduction')->textInput() ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


