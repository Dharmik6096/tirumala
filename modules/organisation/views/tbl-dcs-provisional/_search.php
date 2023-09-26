<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMemberProvisionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php
    $form = ActiveForm::begin([
                'action' => ['dcs-provisional-approval'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcsprovisionalsearch-union_code', 'plant_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcsprovisionalsearch-plant_code', 'mcc_plant_code'); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcsprovisionalsearch-mcc_plant_code', 'bmc_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldcsprovisionalsearch-bmc_code', 'dcs_code'); ?>         
    </div>  
   
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>