<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

//$readonly = $type == 'create' ? FALSE : TRUE;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? true : false;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('scheme_id', $model, $form, 'tblschemeapplicationdisbursement-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('scheme_id'), 'scheme_id', $readonly); ?>
    </div>
    <div class="col-sm-4 <?= $class ?>">
        <?php
        echo Html::hiddenInput('application_status', 'approved', ['id' => 'application_status']);
        echo Html::hiddenInput('application_id', $model->application_id, ['id' => 'application_id']);
        echo Yii::$app->dropdown->welfareSchemeApplication($model, $form, 'tblschemeapplicationdisbursement-scheme_id,application_status,application_id', 'application_id', $model->getAttributeLabel('application_id'), false, false, $readonly);
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'disburse_date', '', false, false, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('ws_payment_mode', $model, $form, '', $model->getAttributeLabel('payment_mode'), false, 'payment_mode', FALSE, FALSE, FALSE); ?>
    </div>
    <div class="clearfix"></div>
    <?php $district = Yii::$app->session->get('Districts'); ?>
    <?= Html::hiddenInput('session-district', $district, ['id' => 'session-district']) ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bankdepended($model, $form, 'session-district', 'bank_code', $model->getAttributeLabel('bank_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Html::hiddenInput('branch_code', '', ['id' => 'branch_code']); ?>
        <?= Html::hiddenInput('ifsc', '', ['id' => 'ifsc']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblschemeapplicationdisbursement-bank_code', '', $model->getAttributeLabel('branch_code'), 'branch_code'); ?>                        
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true]) ?>    
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'bank_account_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'beneficiary_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('relation', $model, $form, '', $model->getAttributeLabel('party_relation'), false, 'party_relation'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'payment_ref_id')->textInput(['maxlength' => true]) ?>   
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'payment_detail')->textInput(['maxlength' => true]) ?>   
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textArea(['maxlength' => true]) ?>
    </div>
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
   $('#tblschemeapplicationdisbursement-bank_code').on('change',function(){
        $('#tblschemeapplicationdisbursement-ifsc').val('');
    });    
    $('#tblschemeapplicationdisbursement-branch_code').on('change',function(){
            var branch = $('#branch_code').val();
            var ifsc = $('#ifsc').val();
            if(branch != '' && ifsc != ''){
                $('#tblschemeapplicationdisbursement-branch_code').val(branch).trigger('select2:select');
                $('#tblschemeapplicationdisbursement-ifsc').val(ifsc);
 
                setTimeout(function(){
                    $('#branch_code').val('');
                    $('#ifsc').val('');
                }, 5000);
               
            } else {
            var id = $('#tblschemeapplicationdisbursement-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblschemeapplicationdisbursement-ifsc').val(obj1.code);
                        }
            });
           } 
    });
    $('#tblschemeapplicationdisbursement-application_id').on('change',function(e){
        if($('#application_id').val() == ''){ 
            var id = $('#tblschemeapplicationdisbursement-application_id').val();
            $('#branch_code').val('');
            $('#ifsc').val('');
            $('#tblschemeapplicationdisbursement-bank_code').val('').trigger('change');
            $('#tblschemeapplicationdisbursement-bank_code').val('').trigger('select2:select');
            $('#tblschemeapplicationdisbursement-branch_code').val('').trigger('change');
            $('#tblschemeapplicationdisbursement-branch_code').val('').trigger('select2:select');                                                          
            $('#tblschemeapplicationdisbursement-bank_account_no').val('');
            $('#tblschemeapplicationdisbursement-beneficiary_name').val('');
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/welfarescheme/tbl-scheme-application-disbursement/bank-detail']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                if(obj1.status=='success'){
                                    var bank = obj1.data;
                                       $('#branch_code').val(bank.branch_code);
                                       $('#ifsc').val(bank.ifsc);
                                       $('#tblschemeapplicationdisbursement-bank_code').val(bank.bank_code).trigger('change');
                                       $('#tblschemeapplicationdisbursement-bank_code').val(bank.bank_code).trigger('select2:select');
                                       $('#tblschemeapplicationdisbursement-bank_account_no').val(bank.bank_account_no);
                                       $('#tblschemeapplicationdisbursement-beneficiary_name').val(bank.beneficiary_name);                              
                                }
                        }
            });
    }        
    });
";
$this->registerJs($script, View::POS_END, 'welfarescheme-bank-detail');
