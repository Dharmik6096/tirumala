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
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<?php
$bmc = !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['bmc_code']) ? Yii::$app->request->queryParams['TblTransporterPayment']['bmc_code'] : '') : ''; 
$from_date = !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['from_date']) ? Yii::$app->request->queryParams['TblTransporterPayment']['from_date'] : '') : ''; 
$to_date = !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['to_date']) ? Yii::$app->request->queryParams['TblTransporterPayment']['to_date'] : '') : ''; 
$union_code = !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['union_code']) ? Yii::$app->request->queryParams['TblTransporterPayment']['union_code'] : '') : ''; 
$model->bmc_code = $bmc;
$model->from_date = $from_date;
$model->to_date = $to_date;
$model->union_code = $union_code;
?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
</div>  
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('bmc_codes', $model, $form, 'tbltransporterpayment-union_code'); ?>
</div>
<div class="col-sm-4 padding-left-0 padding-right-5">
    <div class="form-group">
        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date', 'to_date'); ?>
    </div>
</div>
<?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);  ?>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
</div>