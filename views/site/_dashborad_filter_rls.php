<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$model->from_date = Yii::$app->controls->view_date(date('Y-m-d'));
$model->to_date = Yii::$app->controls->view_date(date('Y-m-d'));
$model->from_shift = empty($model->from_shift) ? 1 : $model->from_shift;
$model->to_shift = empty($model->to_shift) ? 2 : $model->to_shift;
$date_picker_class = !empty($date_picker_class) ? $date_picker_class : 'col-sm-2';
$mcc_class = !empty($mcc_class) ? $mcc_class : 'col-sm-2';
$common_class = 'padding-left-5 padding-right-5';
$form = ActiveForm::begin([
            'action' => ['index'],
            'id' => $id
        ]);
?>
<?php if (isset($mcc_code) && $mcc_code) { ?> 
    <div class="pb10 <?= $mcc_class . ' ' . $common_class ?>">
        <?= Yii::$app->dropdown->mccDropDown($model, $form, 'mcc_code', false, false, $mcc_code); ?>
    </div> 
<?php } ?>
<?php if (isset($bmc_code) && $bmc_code) { ?>
    <div class="col-sm-2 pb10 <?= $common_class ?>">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $mcc_code, 'bmc_code', false, false, $bmc_code); ?>
    </div> 
<?php } ?>
<?php if (isset($dcs_code) && $dcs_code) { ?>
    <div class="col-sm-2 pb10">
        <?= Yii::$app->dropdown->bmc_society($model, $form, $bmc_code, 'dcs_code', false); ?>         
    </div>
<?php } ?>
<?php if (isset($from_date) && $from_date) { ?>
    <div class="<?= $date_picker_class . ' ' . $common_class ?> pb10">
        <?php
        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group ' . $date_picker_class . ' pb10', false, false, false, false, $from_date_id);
        ?>
    </div> 
<?php } ?>
<!--<div class="clearfix"></div>-->
<?php if (isset($from_shift) && $from_shift) { ?>
    <div class="col-sm-2 shift pb10 <?= $common_class ?>">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'from_shift');
        ?>
    </div> 
<?php } ?>
<?php if (isset($to_date) && $to_date) { ?>
    <div class="<?= $date_picker_class . ' ' . $common_class ?> pb10">
        <?php
        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group ' . $date_picker_class . ' pb10', false, false, false, false, $to_date_id);
        ?>
    </div>
<?php } ?>
<?php if (isset($to_shift) && $to_shift) { ?>
    <div class="col-sm-2 shift <?= $common_class ?>">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'to_shift');
        ?>
    </div>  
<?php } ?>
<?php if (isset($hidden_from_date) && $hidden_from_date) { ?>
    <?php
    echo Html::activeHiddenInput($model, 'hidden_from_date', ['value' => $hidden_from_date]);
    ?>  
<?php } ?>
<?php if (isset($hidden_to_date) && $hidden_to_date) { ?>
    <?php
    echo Html::activeHiddenInput($model, 'hidden_to_date', ['value' => $hidden_to_date]);
    ?>  
<?php } ?>
<div class="col-sm-2 pb10 <?= $common_class ?>">
    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id); ?>
</div>
<?php
ActiveForm::end();
?>


<?php
$script = "
    setHtmlData('{$id}','{$container}','{$url}');  
    $('.{$id}').on('click',function(e) {
        e.preventDefault();
        setHtmlData('{$id}','{$container}','{$url}');        
        return false;
    });
    ";
$this->registerJs($script, View::POS_READY, $id);
?>