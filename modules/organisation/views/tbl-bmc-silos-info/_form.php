<?php

use yii\helpers\Html;
?>
<div class="col-sm-3">
    <?= $form->field($model, 'silo_no')->textInput() ?>
</div>
<div class="col-sm-3 number-validate">
    <?= $form->field($model, 'storage_capacity')->textInput() ?>
</div>
<div class="col-sm-3 number-validate">
    <?= $form->field($model, 'chilling_capacity')->textInput() ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdown('manufacture', $model, $form, 'form-group col-sm-12', 'Manufacturer'); ?>
</div>
<div class="col-sm-3">
    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdownStatic('owning_type', $model, $form, 'form-group', $model->getAttributeLabel('owning_type'), false, 'owning_type', false); ?>
</div>
<div class="col-sm-3">
    <?= Html::activeHiddenInput($model, 'bmc_silos_info_code', ['value' => $model->bmc_silos_info_code]) ?>
    <?= $form->field($model, 'description')->textarea() ?>
</div>

