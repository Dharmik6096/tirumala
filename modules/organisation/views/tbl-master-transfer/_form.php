<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'master-transfer-form'],
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Transfer Request For') ?></h4>
        </div>
        <div class="col-sm-2">
            <?php
            echo Yii::$app->dropdown->dropdown('transfer_master_type', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('master_type'), false, 'master_type');
            ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->transfer_type($model, $form, 'tblmastertransfer-master_type', 'transfer_type', $model->getAttributeLabel('transfer_type')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmastertransfer-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box theme_border_right CURRENTINFO reset_field">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Current Details') ?></h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmastertransfer-plant_code', 'old_mcc_plant_code', $model->getAttributeLabel('old_mcc_plant_code')); ?>
        </div>
        <div class="col-sm-2 DCSFARMER">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmastertransfer-old_mcc_plant_code', 'old_bmc_code', $model->getAttributeLabel('old_bmc_code')); ?>
        </div>
        <div class="col-sm-2 DCSFARMER dcs_code">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmastertransfer-old_bmc_code', 'old_dcs_code', $model->getAttributeLabel('old_dcs_code')); ?>         
        </div>
        <div class="col-sm-2 FARMER">
            <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmastertransfer-old_dcs_code', '', $model->getAttributeLabel('old_member_code'), 'old_member_code', FALSE, 0, [], TRUE); ?>
        </div>
        <div class="col-sm-2 vendor_type">
            <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('customer_type'), FALSE, 'customer_type'); ?>
        </div>
        <div class="col-sm-2 vendor_code">
            <?= Yii::$app->dropdown->customer_code($model, $form, 'tblmastertransfer-old_bmc_code,tblmastertransfer-customer_type', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE); ?>
        </div>
        <div class="col-sm-2 DCS">
            <?php //$form->field($model, 'old_route_code')->textInput(['readOnly' => TRUE])  ?>  
        </div>

    </div>
    <div class="col-md-12 padding_10_0 theme-box theme_border_right NEWINFO">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'New Details') ?></h4>
        </div>
        <div class="reset_field">
            <div class="col-sm-2 DCSFARMER">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmastertransfer-plant_code', 'new_mcc_plant_code', $model->getAttributeLabel('new_mcc_plant_code')); ?>
            </div>
            <div class="col-sm-2 DCSFARMER">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmastertransfer-new_mcc_plant_code', 'new_bmc_code', $model->getAttributeLabel('new_bmc_code')); ?>
            </div>
            <div class="col-sm-2 FARMER">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmastertransfer-new_bmc_code', 'new_dcs_code', $model->getAttributeLabel('new_dcs_code')); ?>         
            </div>
            <!--        <div class="col-sm-2 number-validate FARMER">
            <?php //$form->field($model, 'ex_member_code')->textInput()  ?>
                    </div>-->
            <div class="col-sm-2 DCS route">
                <?= Yii::$app->dropdown->all_routes($model, $form, 'tblmastertransfer-plant_code,tblmastertransfer-new_mcc_plant_code,tblmastertransfer-new_bmc_code', 'new_route_code', $model->getAttributeLabel('new_route_code')); ?>
            </div>
            <div class="col-sm-2 DCSFARMER">
                <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE); ?>
            </div>
        </div>
        <div class="col-sm-2 mt10 UPDATETRANSACTION">
            <?= $form->field($model, 'update_transaction', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
    </div>
    <div class="UPDATETRANSACTIONDETAIL">
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'from_datetime', '', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->shift($model, $form, 'from_shift', 'From Shift', 'shift', false) ?>    
        </div>  
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'to_datetime', '', FALSE); ?>
        </div> 
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->shift($model, $form, 'to_shift', 'To Shift', 'shift', false) ?>    
        </div>
    </div>
    <?= Html::hiddenInput('plant', $model->plant_code, ['id' => 'plant']); ?>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "$(document).ready(function(){
     setvisible();
    $(document).on('change', '#tblmastertransfer-master_type', function() { 
      setvisible();
    });
    $(document).on('change', '#tblmastertransfer-transfer_type', function() {
        var plant = $('#plant').val();
            if(plant !=''){
                $('#plant').val('');
            }else{
                $('#tblmastertransfer-plant_code').trigger('select2:select');
                $('#tblmastertransfer-plant_code').val('').trigger('change');
                $('#tblmastertransfer-new_mcc_plant_code').prop('disabled',false);
                $('#master-transfer-form .reset_field input').val('');
                $('#master-transfer-form .reset_field select').val('');
            }             
        setvisible();
    });
function setvisible(){
    $('.CURRENTINFO').hide();
    $('.NEWINFO').hide();        
    $('.UPDATETRANSACTION').hide();
    $('.UPDATETRANSACTIONDETAIL').hide();    

        var master_type = $('#tblmastertransfer-master_type').val();
        var transfer_type = $('#tblmastertransfer-transfer_type').val();
        if(master_type!=''){
            $('.CURRENTINFO').show();
            $('.FARMER').show();
            $('.FARMER').show();
            if(master_type=='DCS'){
                $('.FARMER').hide();
                $('.vendor_type').hide();
                $('.vendor_code').hide();
                $('.dcs_code').show();
            if($('#tblmastertransfer-update_transaction').val()=='1'){
                $('.UPDATETRANSACTION').show();
                $('.UPDATETRANSACTIONDETAIL').show();    
                }
            }else if(master_type=='FARMER'){
                $('.DCS').hide();
                $('.vendor_type').hide();
                $('.vendor_code').hide();
                $('.dcs_code').show();
            }else if(master_type=='CUSTOMER'){
                $('.DCS').hide();
                $('.FARMER').hide();
                $('.vendor_type').show();
                $('.vendor_code').show();
                $('.route').show();
                $('.dcs_code').hide();
                $('#tblmastertransfer-old_dcs_code').val('');
            }
        }
       if(transfer_type!=null && transfer_type!='' && transfer_type!='Loading ...'){
           $('.NEWINFO').show();
        if(transfer_type=='DCS'){
            $('.FARMER').show();
        } else {
            $('.DCS').show();
        }
     }
}

$('#tblmastertransfer-old_mcc_plant_code').on('change', function() {
    $('#tblmastertransfer-old_mcc_plant_code').trigger('select2:select');
    var old_mcc = $(this).val();
    if(old_mcc !='' && old_mcc != null){
        var master_type = $('#tblmastertransfer-master_type').val();
        var transfer_type = $('#tblmastertransfer-transfer_type').val();
        var select2Instance = $('#tblmastertransfer-new_mcc_plant_code').data('select2');
        var resetOptions = select2Instance.options.options;
        $('#tblmastertransfer-new_mcc_plant_code').select2('destroy').select2(resetOptions);
        if(master_type=='FARMER'){
      //  $('#tblmastertransfer-new_mcc_plant_code').val('').trigger('change');
      //  $('#tblmastertransfer-new_mcc_plant_code>option[value='+old_mcc+']').prop('disabled', false);
     //    $('#tblmastertransfer-new_mcc_plant_code>option[value!='+old_mcc+']').prop('disabled', true);
       //     $('#tblmastertransfer-new_mcc_plant_code').val(old_mcc).trigger('change');
         //   $('#tblmastertransfer-new_mcc_plant_code').prop('disabled', true);
        }else if(master_type=='DCS' || master_type=='CUSTOMER'){
            if(transfer_type=='MCC'){
            $('#tblmastertransfer-new_mcc_plant_code>option[value!='+old_mcc+']').prop('disabled', false);
            $('#tblmastertransfer-new_mcc_plant_code>option[value='+old_mcc+']').prop('disabled', true);
            }
         }
    }
   
});

$('#tblmastertransfer-new_bmc_code').on('change', function() {
    $('#tblmastertransfer-new_bmc_code').trigger('select2:select');
    var old_mcc = $('#tblmastertransfer-old_dcs_code').val();
    if(old_mcc !='' && old_mcc != null && old_mcc !=='Loading ...'){
        var master_type = $('#tblmastertransfer-master_type').val();
        var transfer_type = $('#tblmastertransfer-transfer_type').val();
        var select2Instance = $('#tblmastertransfer-new_dcs_code').data('select2');
        var resetOptions = select2Instance.options.options;
        $('#tblmastertransfer-new_dcs_code').select2('destroy').select2(resetOptions);
        if(master_type=='FARMER'){
        $('#tblmastertransfer-new_dcs_code>option[value='+old_mcc+']').prop('disabled', true);
          $('#tblmastertransfer-new_dcs_code').trigger('change');
        }
    }
});

$(document).on('change', '#tblmastertransfer-wef_date', function() {   
            if($('#tblmastertransfer-master_type').val()=='DCS'){
            var req_date = $(this).val().split('-');
            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();
            var current_date = d.getFullYear() + '-' +
            (month<10 ? '0' : '') + month + '-' +
            (day<10 ? '0' : '') + day;
            current_date = current_date.split('-');
            var firstDate = new Date();
            firstDate.setFullYear(req_date[2], (req_date[1] - 1 ), req_date[0]);
            var secondDate = new Date();
            secondDate.setFullYear(current_date[0], (current_date[1] - 1 ), current_date[2]);
            firstDate.setHours(0, 0, 0, 0);
            secondDate.setHours(0, 0, 0, 0)
            if(firstDate <= secondDate){
            $('.UPDATETRANSACTION').show();
            }else{
             $('#tblmastertransfer-update_transaction').prop('checked', false);
             $('.UPDATETRANSACTION').hide();
             $('.UPDATETRANSACTIONDETAIL').hide();
            }
            }else{
            $('#tblmastertransfer-update_transaction').prop('checked', false);
            $('.UPDATETRANSACTION').hide();
            $('.UPDATETRANSACTIONDETAIL').hide();            
            }
});

$('#tblmastertransfer-update_transaction').on('change', function() {
        $('.UPDATETRANSACTIONDETAIL').hide();
   if($('#tblmastertransfer-update_transaction').is(':checked')){
        $('.UPDATETRANSACTIONDETAIL').show();
   }
});

});
";
$this->registerJs($script, View::POS_END, 'transfer-utility-form');
?>