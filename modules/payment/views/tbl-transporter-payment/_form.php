<?php

use app\components\ActiveForm;
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
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', '', false, false); ?>
    </div>
    <?php if ($transporter_type == 1) { ?>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->datewise_transporter_list($model, $form, 'tbltransporterpayment-union_code,tbltransporterpayment-from_date,tbltransporterpayment-to_date', 'transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tbltransporterpayment-transporter_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('vehicle_code')); ?>
        </div>
    <?php } else { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tbltransporterpayment-union_code', 'plant_code', TRUE); ?>
        </div> 
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbltransporterpayment-plant_code', 'mcc_plant_code', TRUE, TRUE); ?>
        </div>      
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->datewise_bmc_list($model, $form, 'tbltransporterpayment-mcc_plant_code,tbltransporterpayment-plant_code,tbltransporterpayment-union_code,tbltransporterpayment-from_date,tbltransporterpayment-to_date', 'bmc_code', $model->getAttributeLabel('bmc_code'), TRUE, '', FALSE, TRUE, TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->all_route_transporter($model, $form, 'tbltransporterpayment-plant_code,tbltransporterpayment-mcc_plant_code,tbltransporterpayment-bmc_code', 'transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE); ?>
        </div>
    <?php } ?>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('Next', $model); ?>                
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>