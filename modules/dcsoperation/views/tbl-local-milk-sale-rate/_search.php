<?php

use yii\widgets\ActiveForm;
?>
<div class="tbl-sub-center-search">
    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2 padding-right-5">
        <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= Yii::$app->dropdown->union($model, $form, 'tbllocalmilksaleratesearch-federation_code', 'union_code', false); ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tbllocalmilksaleratesearch-union_code'); ?>        
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= Yii::$app->dropdown->depend_dropdown('sub-center', $model, $form, 'tbllocalmilksaleratesearch-dcs_code'); ?>        
    </div>
    <div class="col-sm-2 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
