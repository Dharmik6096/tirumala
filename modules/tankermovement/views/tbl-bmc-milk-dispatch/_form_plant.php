<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$disabled = empty($model->bmc_milk_dispatch_code) ? '' : 'disabled';
$bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
$readonly = empty($model->bmc_milk_dispatch_code) ? FALSE : TRUE;
$tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'tanker_movement_with_trip_sub_status', 'PORTAL') == 1 ? TRUE : FALSE;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary([$model, $txn_model]); ?>

<?= Html::activeHiddenInput($model, 'bmc_milk_dispatch_code'); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">PLANT Milk Dispatch Detail</h4>
        </div>
        <div class="row col-md-12 <?= $disabled ?> padding-bottom-20">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <?php if ($readonly) { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'plant_code')->label(Yii::t('app', 'Source Plant'))->dropDownList([$model->plant_code => Yii::$app->general->getforeignkey($model->plantCode, 'name')], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcmilkdispatch-union_code', 'plant_code', Yii::t('app', 'Source Plant'), FALSE, '', $readonly); ?>
                </div>
            <?php } ?>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, $readonly, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'from_shift_code'); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, $readonly, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'to_shift_code'); ?>
            </div>
            <?= Html::hiddenInput('type', 'plant', ['id' => 'type']); ?>
            <?php if (!$tripGenerateBtn) { ?>
                <div class="col-sm-2 filldata" id='transactionDate'>
                    <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, $readonly, true); ?>
                </div>
                <div class="col-sm-2 filldata"> 
                    <?= Yii::$app->dropdown->vehicleMasterOpen($model, $form, 'tblbmcmilkdispatch-plant_code,tblbmcmilkdispatch-union_code,type,NULL,tblbmcmilkdispatch-transaction_date', 'vehicle_code', $model->getAttributeLabel('vehicle_code'), false, '', $readonly); ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2 filldata">
                    <?= Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), $readonly); ?>
                </div>
                <div class="col-sm-2 filldata" id='transactionDate'>
                    <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, $readonly, true); ?>
                </div>
            <?php } ?>
            <?php if ($readonly) { ?>
                <div class="col-sm-2 filldata">
                    <?= $form->field($model, 'trip_code')->dropDownList([$model->trip_code => $model->trip_code], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2 filldata">
                    <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                    <?= Html::hiddenInput('tankerMovementWithTripSubStatus', $tankerMovementWithTripSubStatus, ['id' => 'tankerMovementWithTripSubStatus']); ?>
                    <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'tblbmcmilkdispatch-vehicle_code,tblbmcmilkdispatch-plant_code,tblbmcmilkdispatch-transaction_date,trip_code,type,tankerMovementWithTripSubStatus', 'trip_code', $model->getAttributeLabel('trip_code'), false, '', $readonly); ?>
                </div>
            <?php } ?>
            <div id="addTripButtonDiv" class="col-sm-2 addTripButtonDiv">
                <button id="addTripButton" class="btn btn-primary mb0">Generate Trip</button>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'vehicle_in_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'destination_type'); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::hiddenInput('tankerMovement', 'falseRLS', ['id' => 'tankerMovement']); ?>
                <?= Html::hiddenInput('partyType', 'bmcMilkDispatch', ['id' => 'partyType']); ?>
                <?= Html::hiddenInput('processType', 'tankerMilkDispatch', ['id' => 'processType']); ?>
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblbmcmilkdispatch-destination_type,tblbmcmilkdispatch-union_code,NULL,tankerMovement,NULL,processType', 'destination_code', $model->getAttributeLabel('destination_code'), FALSE, $readonly); ?>
            </div>
            <div class="col-sm-2 mt15 no_pointer_disabled" id="is-last-destination-container">
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_last_destination'); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'tested_by')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Dispatch Transactions') ?></h4>
        </div>
        <div class="col-sm-1">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $txn_model, $form, '', true, FALSE, 'milk_type_code'); ?>
        </div>
        <div class="col-sm-1">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $txn_model, $form, '', true, FALSE, 'milk_quality_type_code'); ?>
        </div>
        <div class="col-sm-1">
            <?= Yii::$app->dropdown->chamberNoList($txn_model, $form, 'tblbmcmilkdispatch-vehicle_code', 'chamber_no', Yii::t('app', 'Chamber No')); ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'shift_of_milk')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'dispatch_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'fat')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'snf')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'water')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'temperature')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'clr')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'protein')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'density')->textInput() ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'lactose')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'freezing_point')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'hsn_code')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'seal_no_top')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'seal_no_bottom')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'seal_no_broken')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'dip_open')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'dip_close')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'dip_diff')->textInput() ?>
        </div>
        <!-- <div class="clearfix"></div> -->
        <div id="transactions-from">

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main mt15" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div class="col-md-12 padding_10_0 theme-box ">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
        <h4 class="theme-box-heading"><?= Yii::t('app', 'Dispatch Transactions Detail') ?></h4>
    </div>
    <div id="transactions-detial">

    </div>
</div>
<div id='trip_auto_generate_data'></div>

<?php
$script = "
var tankerMovementWithTripSubStatus = `$tankerMovementWithTripSubStatus`;
var tripGenerateBtn = `$tripGenerateBtn`;
var isSecondTransaction = `$readonly`;
$(document).ready(function(){
    $('#addTripButtonDiv').hide();
    $('#is-last-destination-container').hide();
    if(!isSecondTransaction) {
        $('#tblbmcmilkdispatch-trip_code').on('change',function() {
            $('#addTripButtonDiv').hide();
            var tripCodeDropdownLength = $('#tblbmcmilkdispatch-trip_code option').length;
            var vehicleCode = $('#tblbmcmilkdispatch-vehicle_code').val();
            var transaction_date = $('#tblbmcmilkdispatch-transaction_date').val();
            if(setData(transaction_date) && setData(transaction_date) && setData(vehicleCode) && setData(vehicleCode) && tripCodeDropdownLength == 1){
                if (tripGenerateBtn) {
                    $('#addTripButtonDiv').show();   
                } 
            } else if ($('#tblbmcmilkdispatch-trip_code option').length === 2) {
                $('#tblbmcmilkdispatch-trip_code').val($('#tblbmcmilkdispatch-trip_code option:last').val());
            }
        });
    }
    $('#addTripButton').on('click', function(e) {
        e.preventDefault();
        
        var vehicleName = $('#tblbmcmilkdispatch-vehicle_code option:selected').text();
        var dispatchDate = $('#tblbmcmilkdispatch-transaction_date').val();

        if (dispatchDate && vehicleName) {
            var data = {
                vehicleValue: $('#tblbmcmilkdispatch-vehicle_code').val(),
                vehicleName: $('#tblbmcmilkdispatch-vehicle_code option:selected').text(),
                dispatchDate: $('#tblbmcmilkdispatch-transaction_date').val(),
                plantValue: $('#tblbmcmilkdispatch-plant_code').val(), 
                plantName: $('#tblbmcmilkdispatch-plant_code option:selected').text(),
                unionValue: $('#tblbmcmilkdispatch-union_code').val(),
                unionName: $('#tblbmcmilkdispatch-union_code option:selected').text(),
            };

            $.ajax({
                type: 'GET',
                url: '" . Url::to(['/tankermovement/tbl-bmc-milk-dispatch/generate-auto-trip']) . "',
                data: data,
                success: function(response) {
                    $('#trip_auto_generate_data').html(response);
                    $('#createTripModal').modal('show');
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        } else {
            var msg = 'Dispatch Date and Vehicle are required';
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
        }
    });

    var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
    if(setData(bmc_milk_dispatch_code)){
        $('#tblbmcmilkdispatchtxn-milk_type_code').focus(); 
    }
    $(document).off('change', '#tblbmcmilkdispatch-trip_code').on('change', '#tblbmcmilkdispatch-trip_code, #tblbmcmilkdispatch-vehicle_code', function() {
        var trip_code = $('#tblbmcmilkdispatch-trip_code').val();
        var vehicle_code = $('#tblbmcmilkdispatch-vehicle_code').val();
        if(setData(trip_code) && setData(vehicle_code)){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['chamber-capacity-details']) . "',
                data: {'trip_code' : trip_code,'vehicle_code':vehicle_code}, 
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if(obj1.status == 'success'){
                        $(document).off('change', '#tblbmcmilkdispatchtxn-chamber_no, #tblbmcmilkdispatchtxn-dispatch_qty').on('change', '#tblbmcmilkdispatchtxn-chamber_no, #tblbmcmilkdispatchtxn-dispatch_qty', function () {
                            var chamber_no = $('#tblbmcmilkdispatchtxn-chamber_no').val();
                            var dispatch_qty = parseFloat($('#tblbmcmilkdispatchtxn-dispatch_qty').val()) || 0;
                            if (setData(chamber_no) && setData(dispatch_qty)) {
                                if(obj1.chamber_wise_data.hasOwnProperty(chamber_no)){
                                    var total_qty = parseFloat(obj1.chamber_wise_data[chamber_no]['total_qty']) || 0;
                                    var capacity = parseFloat(obj1.chamber_wise_data[chamber_no]['capacity']) || 0;
                                    if ((total_qty + dispatch_qty) > capacity) {
                                        bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Dispatch quantity exceeds chamber capacity \"+capacity+\"</span></div></div>\");
                                        $('#tblbmcmilkdispatchtxn-dispatch_qty').val('');
                                    }   
                                } else {
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                }
                            } 
                        });
                    }
                },
            });
        }           
    });
    if(!isSecondTransaction) {
        $(document).on('change', '#tblbmcmilkdispatch-bmc_code, #tblbmcmilkdispatch-trip_code', function() {   
            var source_org_code = $('#tblbmcmilkdispatch-bmc_code').val();
            var trip_code = $('#tblbmcmilkdispatch-trip_code').val();     
            var source_org_type = 'bmc';
            $('.field-tblbmcmilkdispatch-destination_type').removeClass('no_pointer_disabled');
            $('.field-tblbmcmilkdispatch-destination_code').removeClass('no_pointer_disabled');
            if(setData(trip_code) && setData(source_org_code)){
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['vehicle-trip-detail']) . "',
                    data: {'source_org_code' : source_org_code,'trip_code':trip_code,'source_org_type':source_org_type}, 
                    success: function(data) {
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success') {
                            if (obj.data.is_auto_trip == 0 && obj.data.is_last_destination == 0) {
                                var destType = obj.data.destination_type.toUpperCase();
                                $('#tblbmcmilkdispatch-destination_type').val(destType).trigger('change').trigger('select2:select');
                                $('.field-tblbmcmilkdispatch-destination_type').toggleClass('no_pointer_disabled', !!tankerMovementWithTripSubStatus);
                                $('#tblbmcmilkdispatch-destination_code').one('depdrop:afterChange', function() {
                                    setTimeout(function() {
                                        $('#tblbmcmilkdispatch-destination_code').val(obj.data.destination_code).trigger('change').trigger('select2:select');
                                        $('.field-tblbmcmilkdispatch-destination_code').toggleClass('no_pointer_disabled', !!tankerMovementWithTripSubStatus);
                                    }, 1000);
                                });
                            } else if (obj.data.is_auto_trip == 1) {
                                $('#is-last-destination-container').show();
                                $(document).off('change', '#tblbmcmilkdispatch-destination_type').on('change', '#tblbmcmilkdispatch-destination_type', function () {
                                    var destType = $(this).val().toUpperCase();
                                    var isCheckbox = $('#tblbmcmilkdispatch-is_last_destination');
                                    if (destType === 'PLANT' || destType === 'PARTY') {
                                        isCheckbox.prop('checked', true);
                                    } else {
                                        isCheckbox.prop('checked', false);
                                    }
                                });
                            } else {
                                $('#is-last-destination-container').hide();
                            }
                            if (setData(obj.data.arrival_time)) {
                                let arrivalTime = obj.data.arrival_time;
                                let timeOnly = arrivalTime.slice(11, 16); 
                                $('#tblbmcmilkdispatch-vehicle_in_time').val(timeOnly).prop('readonly', true);
                            } else {
                                $('#tblbmcmilkdispatch-vehicle_in_time').val(obj.currentTime).trigger('change').prop('readonly', false);
                            }
                        } else {
                            $('#tblbmcmilkdispatch-vehicle_in_time').val(obj.currentTime).trigger('change').prop('readonly', false);
                        }  
                    }
                });
            }
        });
    }
});

function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    } else {
        return false;
    }
}
";

$script .= "
    $(document).on('change','.filldata', function() {
        var union_code = $('#tblbmcmilkdispatch-union_code').val();
        var from_date = $('#tblbmcmilkdispatch-from_date').val();
        var from_shift = $('#tblbmcmilkdispatch-from_shift_code').val();
        var to_date = $('#tblbmcmilkdispatch-to_date').val();
        var to_shift = $('#tblbmcmilkdispatch-to_shift_code').val();
        var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
        var vehicle_code = $('#tblbmcmilkdispatch-vehicle_code').val();
        if(setData(from_date) && setData(from_shift) && setData(to_date) && setData(to_shift) && setData(vehicle_code)){
            $('#transactions-from').html('');
            $('#transactions-detial').html('');           
            BindData(from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code);            
        }      
    });
    
    function CheckTrip(from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
        $.ajax({
                type: 'get',
                url: '" . Url::to(['check-trip']) . "',
                data: {'from_date' : from_date,'from_shift':from_shift,'to_date' : to_date,'to_shift':to_shift,'vehicle_code' : vehicle_code},             
                success: function(data) {
                    var data=$.parseJSON(data);
                    if (data.status == 'success'){   
                    // $('#tblbmcmilkdispatch-trip_code').val(data.trip_code);
                        BindData(from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code); 
                    }else {
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>' + data.msg + '</span></div></div>');
                    }
                } 
        });   
    }  
  
    function BindData(from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
       
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'union_code':union_code},             
                success: function(data) {
                  $('#transactions-from').html(data);                                                                 
                }
            });            
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-detail']) . "',
                data: {'bmc_milk_dispatch_code' : bmc_milk_dispatch_code,'form_type':'plant'},             
                success: function(data) {
                  $('#transactions-detial').html(data);
               //   $('#loadercontent').hide();
               //   $('#pageloader').hide();  
                },
                error: function(data) {  
                //    $('#loadercontent').hide();
                 //   $('#pageloader').hide();
                }
            });     
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
<?php
$script = "$(document).ready(function(){
    $(document).on('click','.view-config',function(e){
    var id= $(this).attr('data-val');
  ViewConfig(id);
    });
    function ViewConfig(code){
        if(setData(code)){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/tankermovement/tbl-bmc-milk-dispatch/view-config']) . "',
                data: {'id' : code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#config_detail_view').html(data);
                   $('#ConfigModal').modal('toggle');              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }
    }
});";
$this->registerJs($script, View::POS_END, 'bmc-config-popup');
?>
<?php
if (!$readonly) {
    $script = "$(document).ready(function(){
        function formatLocalDate(date) {
            var day = date.getDate().toString().padStart(2, '0');
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var year = date.getFullYear();
            return day + '-' + month + '-' + year;
        }
        $(document).on('change', '#tblbmcmilkdispatch-from_date, #tblbmcmilkdispatch-to_date', function() {
            var from_date = $('#tblbmcmilkdispatch-from_date').val();
            var to_date = $('#tblbmcmilkdispatch-to_date').val();
            if (setData(from_date) && setData(to_date)) {
                // Split date strings and format them as yyyy-mm-dd
                var from_date_parts = from_date.split('-');
                var to_date_parts = to_date.split('-');
                var formatted_from_date = from_date_parts[2] + '-' + from_date_parts[1] + '-' + from_date_parts[0];
                var formatted_to_date = to_date_parts[2] + '-' + to_date_parts[1] + '-' + to_date_parts[0];

                var fromDateObj = new Date(formatted_from_date);
                var toDateObj = new Date(formatted_to_date);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);
                date = formatLocalDate(currentDate);
                if (isNaN(fromDateObj) || isNaN(toDateObj) || toDateObj < fromDateObj) {
                    var errorMessage = 'must not be less than from date.';
                    var errorElement = '<div class=\"error-message error_message\">' + errorMessage + '</div>';
                    $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
                    $('.field-tblbmcmilkdispatch-to_date').append(errorElement);
                } else {
                    $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
                    $('#tblbmcmilkdispatch-transaction_date').kvDatepicker('destroy');
                    $('#tblbmcmilkdispatch-transaction_date').kvDatepicker({
                            format: 'dd-mm-yyyy', // Set your desired date format
                            todayHighlight: true,
                            autoclose: true,
                            endDate: date,
                            startDate: to_date
                        });
                    }  
            } else {
                $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
            }
        });
    });";
    $this->registerJs($script, View::POS_END, 'to-date-from-date');
}
?>