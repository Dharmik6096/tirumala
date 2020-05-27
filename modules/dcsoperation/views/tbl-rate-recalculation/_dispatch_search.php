<?php

use yii\widgets\ActiveForm;
use yii\web\View;

$searchModel->from_date = !empty($searchModel->from_date) ? $searchModel->from_date : date('d-m-Y');
$searchModel->to_date = !empty($searchModel->to_date) ? $searchModel->to_date : date('d-m-Y');
?>

<?php
$form = ActiveForm::begin([
            'method' => 'get',
            'validateOnBlur' => true,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', FALSE, $disable_search); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($searchModel, $form, 'tblraterecalculationsearch-union_code', 'plant_code', false, false, '', $disable_search); ?>
</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($searchModel, $form, 'tblraterecalculationsearch-plant_code', 'mcc_plant_code', false, FALSE, '', $disable_search); ?>
</div>      
<div class="col-sm-2">
    <?= Yii::$app->dropdown->mcc_bmc($searchModel, $form, 'tblraterecalculationsearch-mcc_plant_code', 'bmc_code', false, false, '', '', $disable_search); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->bmc_society($searchModel, $form, 'tblraterecalculationsearch-bmc_code', 'dcs_code', false, FALSE, '', $disable_search); ?>         
</div>  
<?= Yii::$app->dropdown->dropdownStatic('rate_recalc_type', $searchModel, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', FALSE, $disable_search, 'recalc_type', FALSE) ?> 
<div class="clearfix"></div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($searchModel, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, $disable_search, false); ?>
</div>
<div class="col-sm-2 shift">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $searchModel, $form, '', false, $disable_search, 'from_shift'); ?>
</div>   
<div class="col-sm-2">
    <?= Yii::$app->controls->date($searchModel, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, $disable_search, false); ?>
</div>
<div class="col-sm-2 shift">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $searchModel, $form, '', false, $disable_search, 'to_shift'); ?>
</div>
<?php if (!$disable_search) { ?>
    <div class="col-sm-3">
        <?= Yii::$app->controls->search(); ?>
    </div>
<?php } ?>
<?php ActiveForm::end(); ?>

