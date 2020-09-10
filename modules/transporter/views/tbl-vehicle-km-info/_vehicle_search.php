<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row">
    <?php
    $form = ActiveForm::begin([
                'action' => ['create'],
                'method' => 'get',
    ]);
    ?>
    <?php
    $bmc = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code'] : '') : '';
    $shift = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['shift_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['shift_code'] : '') : '';
    $model->bmc_code = $bmc;
    $model->shift_code = $shift;
    ?>

    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
    </div>  



    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehiclemastersearch-union_code', 'plant_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvehiclemastersearch-plant_code', 'mcc_plant_code'); ?>
    </div>  

    <div class="col-sm-3">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvehiclemastersearch-mcc_plant_code', 'bmc_code'); ?>
    </div>

    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', false, false, 'transporter_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, false, false, false); ?>
    </div>
    <div class="col-sm-3 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift', false, false, 'shift_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>