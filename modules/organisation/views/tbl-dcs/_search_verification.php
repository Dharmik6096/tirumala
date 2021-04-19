<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tblcustomermastersearch';
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
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', 'MCC'); ?>
    </div> 
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', 'BMC'); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('master_type', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', 'Verification For', FALSE, 'master_type', FALSE) ?> 

    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
