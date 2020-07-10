<?php

use yii\bootstrap\ActiveForm;
?>


<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="row">   
    <?= Yii::$app->dropdown->dropdownStatic('process_type', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('process_type'), FALSE, 'process_type') ?> 
    <?= Yii::$app->dropdown->dropdownStatic('data_type', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('data_type'), FALSE, 'data_type') ?> 
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbleiplmasterfilelog-union_code', 'plant_code', true); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbleiplmasterfilelog-plant_code', 'mcc_plant_code', true); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbleiplmasterfilelog-mcc_plant_code', 'bmc_code', true); ?>
    </div>
    <div class="col-sm-2">
        <?php //Yii::$app->dropdown->bmc_society($model, $form, 'tbleiplmasterfilelog-bmc_code', 'dcs_code', true, FALSE); ?>  
        <?= Yii::$app->dropdown->depend_dropdown('bmc-dcs', $model, $form, 'tbleiplmasterfilelog-bmc_code', '', $model->getAttributeLabel('dcs_code'), 'dcs_code', FALSE, 1, explode(',', Yii::$app->session->get('Dcs')), TRUE); ?>
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


