<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="payment-release-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'validateOnBlur' => false,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>   
    <div class="row">
        <div class="col-sm-3" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblpermanentholdamountsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div> 
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblpermanentholdamountsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>      
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblpermanentholdamountsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblpermanentholdamountsearch-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'from_date'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'to_date'); ?>
        </div>        
        <div class=" col-sm-2 form-group mt18 mb-5">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
    <?php
        if (!empty($dataProvider->getModels())) {
            ?>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'release_date'); ?>
            </div>
            <?php
        }
        ?>
</div>
