<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['rollback-member'],
        ]);
?>   
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmembersearch-f_union_code', 'f_plant_code'); ?>

</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmembersearch-f_plant_code', 'f_mcc_code'); ?>

</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmembersearch-f_mcc_code', 'f_bmc_code'); ?>

</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmembersearch-f_bmc_code', 'f_dcs_code'); ?>         
</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmembersearch-f_dcs_code'); ?>
</div>   


<div class="col-sm-3">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
