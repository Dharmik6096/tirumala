<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
echo $form->errorSummary($model);
?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('transporter_type', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('transporter_type'), false, 'transporter_type', false); ?>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-3">
        <?php //Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tbltransporterpayment-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
        <?php
        echo Yii::$app->dropdown->datewise_transporter_list($model, $form, 'tbltransporterpayment-union_code,tbltransporterpayment-transporter_type,tbltransporterpayment-from_date,tbltransporterpayment-to_date', 'transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE);
        ?>

    </div>
<!--    <div class="col-sm-3">
        <?PHP //$form->field($model, 'primary_tpt_cost')->textInput(['class' => 'form-control number-validate']) ?>

    </div>-->
</div>
<div class="row">
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('Next', $model); ?>                
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
<?php
$script = "$(document).ready(function(){     
       setPrimaryTransportation();
       $(document).on('change','#tbltransporterpayment-transporter_type', function() { 
        setPrimaryTransportation();
       });   
});

function setPrimaryTransportation(){
    $('#tbltransporterpayment-primary_tpt_cost').parent('div').hide();            
     var billing_type=$('#tbltransporterpayment-transporter_type').val();
           if(billing_type==1){
                $('#tbltransporterpayment-primary_tpt_cost').parent('div').show();            
            }else{
                $('#tbltransporterpayment-primary_tpt_cost').parent('div').hide();            
            }

}
";
$this->registerJs($script, View::POS_READY, 'tpt-cost-script');

