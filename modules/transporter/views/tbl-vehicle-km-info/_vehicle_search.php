<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlantSearch */
/* @var $form yii\widgets\ActiveForm */
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
    
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
</div>  
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('bmc_codes', $model, $form, 'tblvehiclemastersearch-union_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdown('transporter_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0',false,false,'transporter_code');  ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false,false,false,false); ?>
</div>
<div class="col-sm-2 shift">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift', false, false, 'shift_code'); ?>
</div>
<?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);  ?>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
</div>