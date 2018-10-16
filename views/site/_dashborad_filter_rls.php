<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$form = ActiveForm::begin([
            'action' => ['index'],
            'id' => $id
        ]);
?>
<?php if (isset($mcc_code) && $mcc_code) { ?> 
    <div class="col-sm-2 pb10">
        <?= Yii::$app->dropdown->mccDropDown($model, $form, 'mcc_code', false); ?>
    </div> 
<?php } ?>
<?php if (isset($bmc_code) && $bmc_code) { ?>
    <div class="col-sm-2 pb10">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'dashboard-mcc_code', 'bmc_code', false); ?>
    </div> 
<?php } ?>
<?php if (isset($dcs_code) && $dcs_code) { ?>
    <div class="col-sm-2 pb10">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'dashboard-bmc_code', 'dcs_code', false); ?>         
    </div>
<?php } ?>
<?php if (isset($from_date) && $from_date) { ?>
    <div class="col-sm-2 pb10">
        <?php
        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 pb10 padding-left-5 padding-right-5', false, false, false, false, 'milk_coll_from_date');
        ?>
    </div> 
<?php } ?>
<?php if (isset($from_shift) && $from_shift) { ?>
    <div class="col-sm-2 pb10">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'from_shift');
        ?>
    </div> 
<?php } ?>
<?php if (isset($to_date) && $to_date) { ?>
    <div class="col-sm-2 pb10">
        <?php
        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 pb10 padding-left-5 padding-right-5', false, false, false, false);
        ?>
    </div>
<?php } ?>
<?php if (isset($to_shift) && $to_shift) { ?>
    <div class="col-sm-2">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'to_shift');
        ?>
    </div>  
<?php } ?>
<div class="col-sm-2 pb10">
    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id); ?>
</div>
<?php
ActiveForm::end();
?>


<?php
$script = "
    $('.{$id}').on('click',function(e) {
        e.preventDefault();
        setHtmlData('{$id}','{$container}','{$url}');        
        return false;
    });
    ";
$this->registerJs($script, View::POS_READY, $id);
?>