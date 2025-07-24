<?php

use yii\widgets\ActiveForm;
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberprovisionalsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberprovisionalsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberprovisionalsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmemberprovisionalsearch-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>         
    </div>  
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>