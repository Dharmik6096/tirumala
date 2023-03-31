<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Remuneration Payment Process : Step 1';
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>

        <div class="row">
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblremunerationsummary-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblremunerationsummary-plant_code', 'mcc_plant_code', TRUE); ?>
            </div>      
            <div  id="single-bmc" class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblremunerationsummary-mcc_plant_code', 'bmc_code', 'BMC *'); ?>
            </div>
            <div id="multiple-bmc" class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblremunerationsummary-mcc_plant_code', 'p_bmc_code', 'BMC *', TRUE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->RemunerationPaymentCycle($model, $form, 'tblremunerationsummary-union_code,tblremunerationsummary-bmc_code,tblremunerationsummary-p_bmc_code', 'payment_cycle_code', 'Payment Cycle'); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= $form->field($model, 'calculate_milk_recovey', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= $form->field($model, 'calculate_other_head', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>

            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>   
                    <?= Yii::$app->controls->cancel(); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
     $('#single-bmc').hide();
     $('#multiple-bmc').hide();  
$('#tblremunerationsummary-mcc_plant_code').on('change',function(){
     var mcc_plant_code= $(this).val();
     if(mcc_plant_code !='' && mcc_plant_code != null){
            $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-vsp-payment/check-mcc-type']) . "',
            data: {'mcc_plant_code' : mcc_plant_code},            
            success: function(data) {
                var data = $.parseJSON(data);
                var multiple_bmc = data.multiple_bmc;
               if(multiple_bmc == '1'){
                 $('#single-bmc').hide();
                 $('#multiple-bmc').show();
               }else{
                 $('#multiple-bmc').hide();
                 $('#single-bmc').show();
               }   
            }
        });
      }
});
";
$this->registerJs($script, View::POS_END, 'check-mcc-type');
?>
