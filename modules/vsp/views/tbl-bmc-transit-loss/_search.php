<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="tbl-bmc-transit-loss-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'post',
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmctransitloss-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmctransitloss-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmctransitloss-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblbmctransitloss-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', FALSE); ?>         
    </div> 
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?php
        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false);
        ?>
    </div>    
    <div class="col-sm-2 shift">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift');
        ?>
    </div>  
    <div class="col-sm-2">
        <?php
        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false);
        ?>
    </div>    
    <div class="col-sm-2 shift">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift');
        ?>
    </div>  
    <?php if (empty($dataProvider->getModels())) { ?>
        <div class="col-sm-3 mt23">
            <?= Html::submitButton(Yii::t('app', 'GENERATE'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>
