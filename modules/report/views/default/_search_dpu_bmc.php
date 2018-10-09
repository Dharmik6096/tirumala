<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? date('d-m-Y') : $request['min_date'];
$max_date = empty($request['max_date']) ? date('d-m-Y') : $request['max_date'];
$model->shift = empty($model->shift) ? '3' : $model->shift;
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
?>

<div class="grid-search large-search hidden-print">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'id' => 'village-form',
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-12">
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
        </div>
        <div class="col-sm-2 padding-left-0 padding-right-5">
            <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-union_code', 'plant_code'); ?>
        </div> 

        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-plant_code', 'mcc_code'); ?>
        </div>      
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-mcc_code', 'bmc_code'); ?>
        </div>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= Yii::$app->dropdown->bmc_society($model, $form, $field_class . '-bmc_code', 'dcs_code'); ?>         
    </div>
    <div class="col-sm-4 padding-left-0 padding-right-5">
        <div class="form-group">
            <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
        </div>
    </div>
    <?php if (!empty($shiftFilter)) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
        </div>   
    <?php } ?>
    <div class="col-sm-2 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

