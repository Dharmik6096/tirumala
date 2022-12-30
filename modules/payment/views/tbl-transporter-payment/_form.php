<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
echo $form->errorSummary($model);
?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('transporter_type', $model, $form, 'form-group', $model->getAttributeLabel('transporter_type'), false, 'transporter_type', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbltransporterpayment-union_code', 'plant_code', TRUE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbltransporterpayment-plant_code', 'mcc_plant_code', TRUE); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->datewise_bmc_list($model, $form, 'tbltransporterpayment-union_code,tbltransporterpayment-plant_code,tbltransporterpayment-mcc_plant_code,tbltransporterpayment-transporter_type,tbltransporterpayment-from_date,tbltransporterpayment-to_date', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->all_route_transporter($model, $form, 'tbltransporterpayment-plant_code,tbltransporterpayment-mcc_plant_code,tbltransporterpayment-bmc_code', 'transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('Next', $model); ?>                
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>