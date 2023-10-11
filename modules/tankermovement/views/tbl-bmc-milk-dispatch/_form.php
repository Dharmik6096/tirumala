<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$disabled = empty($model->bmc_milk_dispatch_code) ? '' : 'disabled';
$bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
$readonly = empty($model->bmc_milk_dispatch_code) ? FALSE : TRUE;
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
            <h4 class="theme-box-heading">BMC Milk Dispatch Detail</h4>
        </div>
        <div class="col-md-8 micro_form <?= $disabled ?> padding-bottom-20">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcmilkdispatch-union_code', 'plant_code', TRUE, FALSE, '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcmilkdispatch-plant_code', 'mcc_plant_code', TRUE, FALSE, '', $readonly); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcmilkdispatch-mcc_plant_code', 'bmc_code', TRUE, FALSE, '', '', $readonly); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'from_shift_code'); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'to_shift_code'); ?>
            </div>
            <div class="col-sm-2 filldata"> 
                <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblbmcmilkdispatch-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'tblbmcmilkdispatch-vehicle_code,tblbmcmilkdispatch-transaction_date,trip_code', 'trip_code', $model->getAttributeLabel('trip_code'), false, '', $readonly); ?>
            </div>
            <div id="addTripButtonDiv" class="col-sm-4 addTripButtonDiv">
                <button id="addTripButton" class="btn btn-primary">Generate Trip</button>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'vehicle_in_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'vehicle_out_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'destination_type'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblbmcmilkdispatch-destination_type,tblbmcmilkdispatch-union_code,tblbmcmilkdispatch-bmc_code', 'destination_code', $model->getAttributeLabel('destination_code'), FALSE, $readonly); ?>
            </div>
            <!--        <div class="col-sm-2 mt15">
            <?php //$form->field($model, 'is_last_destination', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox();    ?>
                    </div>-->
            <div class="col-sm-4"> 
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>
        </div>
        <div class="col-lg-4">
            <h5 class="panel-heading mb15"><?= Yii::t('app', 'Purchase Information') ?></h5>
            <div id="purchase-detial">
                <table class="table tab-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Milk Type</th>
                            <th>Quality Type</th>
                            <th>Silo No.</th>                 
                            <th>Purchase Qty</th>
                            <th>Balance Qty</th>    
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
            <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmcmilkdispatch-module_name']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $txn_model, $form, 'tblbmcmilkdispatch-bmc_code,tblbmcmilkdispatch-module_name', 'form-group col-sm-4', $txn_model->getAttributeLabel('bmc_silos_info_code'), ''); ?>
        </div>
        <div class="col-sm-1"> 
            <?= Yii::$app->dropdown->dropdownStatic('chamber_no', $txn_model, $form, 'form-group', $txn_model->getAttributeLabel('chamber_no'), false, 'chamber_no', false); ?>
        </div>
        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'dispatch_qty')->textInput() ?>
        </div>
        <div class="col-sm-1"> 
            <?= Yii::$app->dropdown->dropdown('qty_diff_type', $txn_model, $form, '', true, false, 'qty_diff_type_code'); ?>
        </div>
        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'qty_diff')->textInput() ?>
        </div>

        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'balance_qty')->textInput() ?>
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
        <div class="clearfix"></div>
        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'clr')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'protein')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate"> 
            <?= $form->field($txn_model, 'density')->textInput() ?>
        </div>
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
        <div class="clearfix"></div>
        <div id="transactions-from">

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main mt15" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
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
$(document).ready(function(){
    $('#addTripButtonDiv').hide();
    $('#tblbmcmilkdispatch-vehicle_code').on('change',function() {
        setTimeout(function(){        
            var tripCodeDropdownLength = $('#tblbmcmilkdispatch-trip_code option').length - 1;
            $('#addTripButtonDiv').hide();
            var vehicleCode = $('#tblbmcmilkdispatch-vehicle_code').val();
            if (vehicleCode != '' && vehicleCode != null && tripCodeDropdownLength == 0) {
                $('#addTripButtonDiv').show();  
            }
        },2000);
    });

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
                mccValue: $('#tblbmcmilkdispatch-mcc_plant_code').val(), 
                mccName: $('#tblbmcmilkdispatch-mcc_plant_code option:selected').text(),
                bmcValue: $('#tblbmcmilkdispatch-bmc_code').val(),
                bmcName: $('#tblbmcmilkdispatch-bmc_code option:selected').text(),
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
    if(bmc_milk_dispatch_code!=''){
        $('#tblbmcmilkdispatchtxn-milk_type_code').focus(); 
    }


    $(document).on('change','#tblbmcmilkdispatchtxn-dispatch_qty,#tblbmcmilkdispatchtxn-qty_diff', function() {
        var rows = $('#purchase_detail_tabel tbody tr');    
        var totalQty = 0;
        var totalBalanceQty = 0;

        rows.each(function() {
            var purchaseQty = parseFloat($(this).find('#purchase_qty').text()) || 0;
            var previousQty = parseFloat($(this).find('#previous_qty').text()) || 0;
            var rowTotalQty = purchaseQty + previousQty;
            totalQty += rowTotalQty;
        });        

        var dispatch_qty = parseFloat($('#tblbmcmilkdispatchtxn-dispatch_qty').val()) || 0;
        var qty_diff = parseFloat($('#tblbmcmilkdispatchtxn-qty_diff').val()) || 0;
        var balance_qty = parseFloat(totalQty) - (dispatch_qty + qty_diff);

        if (!isNaN(balance_qty)) {
            balance_qty = Math.max(0, balance_qty);
        } else {
            balance_qty = 0;
        }
        $('#tblbmcmilkdispatchtxn-balance_qty').val((balance_qty).toFixed(2));
    });
});

$(document).on('change','#tblbmcmilkdispatchtxn-dispatch_qty,#tblbmcmilkdispatchtxn-rtpl', function() {
    var rtpl=$('#tblbmcmilkdispatchtxn-rtpl').val();
    var dispatch_qty=$('#tblbmcmilkdispatchtxn-dispatch_qty').val();
    if(rtpl !='' && dispatch_qty!=''){
        $('#tblbmcmilkdispatchtxn-amount').val(parseFloat(rtpl*dispatch_qty).toFixed(2))
    } 
});

$(document).on('change','#tblbmcmilkdispatchtxn-qty_diff_type_code', function() {
    var qtyDiffTypeCode = $(this).val();
    
    if (qtyDiffTypeCode === '1') {
        $('#tblbmcmilkdispatchtxn-qty_diff').prop('readonly', true);
        $('#tblbmcmilkdispatchtxn-qty_diff').val('0');
    } else {
        $('#tblbmcmilkdispatchtxn-qty_diff').prop('readonly', false);
        $('#tblbmcmilkdispatchtxn-qty_diff').val('');
    }
});

";

$script .= "
    $(document).on('change','.filldata', function() {
        var bmc_code = $('#tblbmcmilkdispatch-bmc_code').val();
        var union_code = $('#tblbmcmilkdispatch-union_code').val();
        var from_date = $('#tblbmcmilkdispatch-from_date').val();
        var from_shift = $('#tblbmcmilkdispatch-from_shift_code').val();
        var to_date = $('#tblbmcmilkdispatch-to_date').val();
        var to_shift = $('#tblbmcmilkdispatch-to_shift_code').val();
        var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
        var vehicle_code = $('#tblbmcmilkdispatch-vehicle_code').val();
       if(from_date != '' && from_shift !='' && to_date != '' && to_shift !='' && bmc_code != '' && vehicle_code !=''){
          //  $('#loadercontent').show();
          //  $('#pageloader').show();
            $('#purchase-detial').html('');
            $('#transactions-from').html('');
            $('#transactions-detial').html('');           
            BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code);            
        }      
    });
    
   function CheckTrip(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
      $.ajax({
                type: 'get',
                url: '" . Url::to(['check-trip']) . "',
                data: {'from_date' : from_date,'from_shift':from_shift,'to_date' : to_date,'to_shift':to_shift,'bmc_code' : bmc_code,'vehicle_code' : vehicle_code},             
                success: function(data) {
                  var data=$.parseJSON(data);
                  if (data.status == 'success'){   
                   // $('#tblbmcmilkdispatch-trip_code').val(data.trip_code);
                    BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code); 
                }else {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>' + data.msg + '</span></div></div>');
                }
               } 
            });   
   }  
  
    function BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
       
         $.ajax({
                type: 'get',
                url: '" . Url::to(['purchase-detail']) . "',
                data: {'from_date' : from_date,'from_shift':from_shift,'to_date' : to_date,'to_shift':to_shift,'bmc_code' : bmc_code},             
                success: function(data) {
                  $('#purchase-detial').html(data);                                                                 
                }
            });
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'bmc_code' : bmc_code,'union_code':union_code},             
                success: function(data) {
                  $('#transactions-from').html(data);                                                                 
                }
            });            
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-detail']) . "',
                data: {'bmc_milk_dispatch_code' : bmc_milk_dispatch_code},             
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
        if(code != ''){         
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
$script = "$(document).ready(function(){
    $(document).on('change', '#tblbmcmilkdispatch-from_date, #tblbmcmilkdispatch-to_date', function() {
        var from_date = $('#tblbmcmilkdispatch-from_date').val();
        var to_date = $('#tblbmcmilkdispatch-to_date').val();
        
        if (from_date !== '' && to_date !== '') {
            // Split date strings and format them as yyyy-mm-dd
            var from_date_parts = from_date.split('-');
            var to_date_parts = to_date.split('-');
            var formatted_from_date = from_date_parts[2] + '-' + from_date_parts[1] + '-' + from_date_parts[0];
            var formatted_to_date = to_date_parts[2] + '-' + to_date_parts[1] + '-' + to_date_parts[0];
            
            var fromDateObj = new Date(formatted_from_date);
            var toDateObj = new Date(formatted_to_date);

            if (isNaN(fromDateObj) || isNaN(toDateObj) || toDateObj < fromDateObj) {
                var errorMessage = 'must not be less than from date.';
                var errorElement = '<div class=\"error-message\" style=\"font-size: 8px; margin-bottom: -12px; color:rgb(122, 35, 28);\">' + errorMessage + '</div>';
                $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
                $('.field-tblbmcmilkdispatch-to_date').append(errorElement);
            } else {
                $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
            }
        } else {
            $('.field-tblbmcmilkdispatch-to_date .error-message').remove();
        }
    });
});";
$this->registerJs($script, View::POS_END, 'to-date-from-date');
?>
