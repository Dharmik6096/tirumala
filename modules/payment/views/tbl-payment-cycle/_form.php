<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPaymentCycle */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<div class="row">   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->shift($model, $form, 'from_shift', 'From Shift', 'shift', false) ?>    
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->shift($model, $form, 'to_shift', 'To Shift', 'shift', false) ?>    
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'interval_value')->textInput() ?>
    </div>  
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'check_month', ['checkboxTemplate' => '<div class="checkbox">{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}',])->checkbox(); ?>
    </div>
    <!--    <div class="col-sm-2 mt35">
            <? = Yii::$app->controls->active($model, $form); ?>
        </div>-->
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
            $('#tblpaymentcycle-interval_value').on('blur',function(e){           
                   if(jQuery.inArray($(this).val(), ['5','10','15']) != -1)
                       $('.field-tbldcspaymentcycle-check_month').show(); 
                   else
                        $('.field-tbldcspaymentcycle-check_month').hide(); 
            });
";
$script .= " $('#tblpaymentcycle-interval_value').on('keyup',function(e){           
                   if(jQuery.inArray($(this).val(), ['5','10','15']) != -1)
                       $('.field-tblpaymentcycle-check_month').show(); 
                   else
                        $('.field-tblpaymentcycle-check_month').hide(); 
            });";
$this->registerJs($script, View::POS_END, 'payment-cycle-check-month');
?>

<?php
$script = "
   $(document).ready(function() {
        $('.shift select option:contains(\'All\')').remove();
         
    });
";
$this->registerJs($script, View::POS_END, 'shift-remvoe');
?>