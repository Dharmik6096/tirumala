<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tblweightcollectionsearch';
$model->from_date = !empty($model->from_date) ? $model->from_date : NULL;
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
//                'action' => ['delete-map-route'],
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC')); ?>
    </div> 
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', 'BMC'); ?>
    </div>

    <div class="col-sm-2 hide_rate_cal">
        <?= Yii::$app->dropdown->customer_type($model, $form, $depend . '-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
    </div> 
    <div class="col-sm-2 hide_rate_cal">
        <?= Yii::$app->dropdown->customer_code($model, $form, $depend . '-bmc_code,' . $depend . '-customer_type', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
