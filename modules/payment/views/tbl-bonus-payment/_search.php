<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<?php
$form = ActiveForm::begin([
            'method' => 'get',
        ]);
?>   

<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbonuspaymentsummary-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbonuspaymentsummary-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbonuspaymentsummary-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), TRUE); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->BonusPaymentCycle($model, $form, 'tblbonuspaymentsummary-union_code,tblbonuspaymentsummary-bmc_code', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code')); ?>
</div>

<div class="form-group padding_top_20">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
