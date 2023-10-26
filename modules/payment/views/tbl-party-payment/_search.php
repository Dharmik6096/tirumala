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
    <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', $searchModel->getAttributeLabel('union_code')); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdown('party_master', $searchModel, $form, '', 'Party'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdownStatic('party_payment_type', $searchModel, $form, '', 'Payment Type'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->PartyPaymentCycle($searchModel, $form, 'tblpartypaymentsearch-party_master_code,tblpartypaymentsearch-payment_type,tblpartypaymentsearch-union_code', 'payment_cycle_code', $searchModel->getAttributeLabel('payment_cycle_code')); ?>
</div>
<div class="form-group padding_top_20">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
