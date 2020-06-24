<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div> 
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectionsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2 height100">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectionsearch-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>         
    </div> 
    <?php if (empty($dataProvider->getModels())) { ?>
        <div class="col-sm-3 mt25">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
