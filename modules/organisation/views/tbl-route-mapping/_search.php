<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$depend = $customer == 1 ? 'tblcustomermastersearch' : 'tbldcssearch';
//echo "<pre>";
//print_r($depend);
//echo "</pre>";
//die;
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['delete-map-route'],
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
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($model, $form, $depend . '-bmc_code', 'customer_type', Yii::t('app', 'Type'), FALSE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->all_routes($model, $form, $depend . '-plant_code,' . $depend . '-mcc_plant_code,' . $depend . '-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
    </div>
    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
