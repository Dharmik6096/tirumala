<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$depend = 'tblmemberpaymentrestrictsearch';
?>

<div class="tbl-member-payment-restrict-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div> 
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>

    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
