<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
            <h4 class="theme-box-heading">BMC Milk Dispatch Detail</h4>
        </div>
        <div class="col-md-8 micro_form <?= $disabled ?> padding-bottom-20">
            <?php echo Html::hiddenInput('is_clr_input', 0, ['id' => 'is_clr_input']); ?>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <?php if ($readonly) { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'plant_code')->dropDownList([$model->plant_code => Yii::$app->general->getforeignkey($model->plantCode, 'name')], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'mcc_plant_code')->dropDownList([$model->mcc_plant_code => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name')], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcmilkdispatch-union_code', 'plant_code', TRUE, FALSE, '', $readonly); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcmilkdispatch-plant_code', 'mcc_plant_code', TRUE, FALSE, '', $readonly); ?>
                </div>
            <?php } ?>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcmilkdispatch-mcc_plant_code', 'bmc_code', TRUE, FALSE, '', '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, false, true); ?>
            </div>
            <div class="col-sm-2 shift">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, false, 'from_shift_code'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, false, true); ?>
            </div>
            <div class="col-sm-2 shift">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, false, 'to_shift_code'); ?>
            </div>
            <?= Html::hiddenInput('type', 'bmc', ['id' => 'type']); ?>
            <?php if (!$tripGenerateBtn) { ?>
                <div class="col-sm-2" id='transactionDate'>
                    <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, $readonly, true); ?>
                </div>
                <?php if ($readonly) { ?>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'vehicle_code')->dropDownList([$model->vehicle_code => Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no')], ['disabled' => 'disabled', 'prompt' => '']) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-2"> 
                        <?= Yii::$app->dropdown->vehicleMasterOpen($model, $form, 'tblbmcmilkdispatch-bmc_code,tblbmcmilkdispatch-union_code,type,NULL,tblbmcmilkdispatch-transaction_date', 'vehicle_code', $model->getAttributeLabel('vehicle_code'), false, '', $readonly); ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), $readonly); ?>
                </div>
                <?php if ($readonly) { ?>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'vehicle_code')->dropDownList([$model->vehicle_code => Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no')], ['disabled' => 'disabled', 'prompt' => '']) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-2" id='transactionDate'>
                        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, $readonly, true); ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if ($readonly) { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'trip_code')->dropDownList([$model->trip_code => $model->trip_code], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2">
                    <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                    <?= Html::hiddenInput('tankerMovementWithTripSubStatus', $tankerMovementWithTripSubStatus, ['id' => 'tankerMovementWithTripSubStatus']); ?>
                    <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'tblbmcmilkdispatch-vehicle_code,tblbmcmilkdispatch-bmc_code,tblbmcmilkdispatch-transaction_date,trip_code,type,tankerMovementWithTripSubStatus', 'trip_code', $model->getAttributeLabel('trip_code'), false, '', $readonly); ?>
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
            <?php if ($readonly) { 
                $response = Yii::$app->general->getColumnName($model->destination_type);
                $sourceData = $model->{$response['rel'] . 'Dest'};
                ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'destination_code')->dropDownList([$model->destination_code => $sourceData[$response['name']] . '-' . $sourceData[$response['ref_code']]], ['disabled' => 'disabled', 'prompt' => '']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2">
                    <?= Html::hiddenInput('tankerMovement', 'falseRLS', ['id' => 'tankerMovement']); ?>
                    <?= Html::hiddenInput('partyType', 'bmcMilkDispatch', ['id' => 'partyType']); ?>
                    <?= Html::hiddenInput('processType', 'tankerMilkDispatch', ['id' => 'processType']); ?>
                    <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblbmcmilkdispatch-destination_type,tblbmcmilkdispatch-union_code,tblbmcmilkdispatch-bmc_code,tankerMovement,partyType,processType', 'destination_code', $model->getAttributeLabel('destination_code'), FALSE, $readonly); ?>
                </div>
            <?php } ?>

            <div class="col-sm-2 mt15 no_pointer_disabled" id="is-last-destination-container">
                <?= $form->field($model, 'is_last_destination', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'tested_by')->textInput() ?>
            </div>
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
        <div class="<?= $txnEdit ? 'no_pointer_disabled' : ''; ?>">
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->dropdown('milk_type_code', $txn_model, $form, '', true, FALSE, 'milk_type_code'); ?>
            </div>
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $txn_model, $form, '', true, FALSE, 'milk_quality_type_code'); ?>
            </div>
            <?php if ($txnEdit) { ?>
                <div class="col-sm-1">
                    <?= $form->field($txn_model, 'bmc_silos_info_code')->dropDownList([$model->bmcSilosInfoList], ['readonly' => true, 'prompt' => 'Select Silo']) ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-1">
                    <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmcmilkdispatch-module_name']); ?>
                    <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $txn_model, $form, 'tblbmcmilkdispatch-bmc_code,tblbmcmilkdispatch-module_name', 'form-group col-sm-4', $txn_model->getAttributeLabel('bmc_silos_info_code')); ?>
                </div>
            <?php } ?>
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->chamberNoList($txn_model, $form, 'tblbmcmilkdispatch-vehicle_code', 'chamber_no', Yii::t('app', 'Chamber No')); ?>
            </div>
        </div>
        <div class="col-sm-1">
            <?= $form->field($txn_model, 'shift_of_milk')->textInput() ?>
        </div>
        <?php if ($txnEdit) { ?>
            <div class="col-sm-1 number-validate no_pointer_disabled">
                <?= $form->field($txn_model, 'original_dispatch_qty')->textInput(['readonly' => true, 'onkeydown' => 'return false;']) ?>
            </div>
        <?php } ?>
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
        <?php if ($txnEdit) { ?>
            <div class="clearfix"></div>
        <?php } ?>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'clr')->textInput() ?>
        </div>
        <?php if (!$txnEdit) { ?>
            <div class="clearfix"></div>
        <?php } ?>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'temperature')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($txn_model, 'water')->textInput() ?>
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
        <?= Html::activeHiddenInput($txn_model, 'physical_stock_only'); ?>
        <?= Html::activeHiddenInput($txn_model, 'is_clr_input'); ?>
        <?= Html::activeHiddenInput($txn_model, 'bmc_milk_dispatch_txn_code'); ?>
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
var isTransactionFormLoad = false;
var isTransactionDetailLoad = false;
var tankerMovementWithTripSubStatus = `$tankerMovementWithTripSubStatus`;
var tripGenerateBtn = `$tripGenerateBtn`;
var isSecondTransaction = `$readonly`;
var txnEdit = `$txnEdit`;
$(document).ready(function(){
    $('#addTripButtonDiv').hide();
    $('#is-last-destination-container').hide();
    var destType = $('#tblbmcmilkdispatch-destination_type').val().toUpperCase();
    updateLastDestinationCheckbox(destType);
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
    if(setData(bmc_milk_dispatch_code)){
        $('#tblbmcmilkdispatchtxn-milk_type_code').focus(); 
    }


    $(document).on('change', '#tblbmcmilkdispatchtxn-milk_type_code, #tblbmcmilkdispatchtxn-milk_quality_type_code, #tblbmcmilkdispatchtxn-bmc_silos_info_code, #tblbmcmilkdispatchtxn-dispatch_qty, #tblbmcmilkdispatchtxn-qty_diff, #tblbmcmilkdispatchtxn-qty_diff_type_code', function() {        
        var milkTypeCode = $('#tblbmcmilkdispatchtxn-milk_type_code').val();
        var milkQualityTypeCode = $('#tblbmcmilkdispatchtxn-milk_quality_type_code').val();
        var bmcSiloInfoCode = $('#tblbmcmilkdispatchtxn-bmc_silos_info_code').val();
        var originalDispatchQty = $('#tblbmcmilkdispatchtxn-original_dispatch_qty').val();
        var stockDetailArray = [];
        var check_key = bmcSiloInfoCode+ '_' + milkTypeCode + '_' + milkQualityTypeCode;
        if(setData(milkTypeCode) && setData(bmcSiloInfoCode) && setData(milkQualityTypeCode)) {
            var data = $('#stockdetail').val();
            if(setData(data)){
                stockDetailArray = jQuery.parseJSON(data);
                var stockDetail = stockDetailArray[check_key];
                var totalQty = 0;
                if (stockDetail) {
                    var previousQty = stockDetail.previous_qty || 0;
                    var purchaseQty = stockDetail.purchase_qty || 0;
                    var totalQty = parseFloat(purchaseQty) + parseFloat(previousQty);
                    if(txnEdit && !isNaN(originalDispatchQty)){
                        totalQty = totalQty + parseFloat(originalDispatchQty);
                    }
                }
            }
        }
        var dispatch_qty = parseFloat($('#tblbmcmilkdispatchtxn-dispatch_qty').val()) || 0;
        var qty_diff = parseFloat($('#tblbmcmilkdispatchtxn-qty_diff').val()) || 0;
        var balance_qty = parseFloat(totalQty) - (dispatch_qty + qty_diff);
        
        if(!txnEdit){
            if (!isNaN(balance_qty)) {
                balance_qty = Math.max(0, balance_qty);
            } else {
                balance_qty = 0;
            }
        }
        
        $('#tblbmcmilkdispatchtxn-balance_qty').val((balance_qty).toFixed(2));
        if (txnEdit && dispatch_qty > totalQty) {
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Dispatch quantity cannot be greater than available quantity!</span></div></div>\");
            $('#tblbmcmilkdispatchtxn-dispatch_qty').val('');
        } else if(txnEdit && balance_qty < 0){
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Balance quantity cannot be less than 0!</span></div></div>\");
            $('#tblbmcmilkdispatchtxn-dispatch_qty').val('');
        }
    });
});

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
$(document).off('change', '#tblbmcmilkdispatch-destination_type').on('change', '#tblbmcmilkdispatch-destination_type', function () {
    var destType = $(this).val().toUpperCase();
    updateLastDestinationCheckbox(destType);
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
                data: {'source_org_code' : source_org_code,'trip_code':trip_code,'source_org_type':source_org_type,'tankerMovementWithTripSubStatus':tankerMovementWithTripSubStatus}, 
                success: function(data) {
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        if (obj.data.is_auto_trip == 0 && obj.data.is_last_destination == 0) {
                            var destType = obj.data.destination_type.toUpperCase();
                            $('#tblbmcmilkdispatch-destination_type').val(destType).trigger('change').trigger('select2:select');
                            $('.field-tblbmcmilkdispatch-destination_type').toggleClass('no_pointer_disabled', !!tankerMovementWithTripSubStatus);
                            $('#tblbmcmilkdispatch-destination_code').one('depdrop.afterChange', function() {
                                setTimeout(function() {
                                    $('#tblbmcmilkdispatch-destination_code').val(obj.data.destination_code).trigger('change').trigger('select2:select');
                                    $('.field-tblbmcmilkdispatch-destination_code').toggleClass('no_pointer_disabled', !!tankerMovementWithTripSubStatus);
                                }, 1000);
                            });
                        } else if (obj.data.is_auto_trip == 1) {
                            if(setData(obj.data.destination_type) && setData(obj.data.destination_code)){
                                var destType = obj.data.destination_type.toUpperCase();
                                $('#tblbmcmilkdispatch-destination_type').val(destType).trigger('change').trigger('select2:select');
                                setTimeout(function() {
                                    $('#tblbmcmilkdispatch-destination_code').val(obj.data.destination_code).trigger('change').trigger('select2:select');
                                }, 1000);
                                updateLastDestinationCheckbox(destType);
                            }
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

function updateLastDestinationCheckbox(destType) {
    var isCheckbox = $('#tblbmcmilkdispatch-is_last_destination');
    if (destType === 'PLANT' || destType === 'PARTY') {
        $('#is-last-destination-container').show();
        isCheckbox.prop('checked', true);
    } else {
        $('#is-last-destination-container').hide();
        isCheckbox.prop('checked', false);
    }
}

function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    }else {
        return false;
    }
}   

$(document).on('change', '#tblbmcmilkdispatch-bmc_code, #tblbmcmilkdispatchtxn-fat, #tblbmcmilkdispatchtxn-clr, #tblbmcmilkdispatchtxn-snf', function() {
    calculateClr();
});

function calculateClr(){
    var union = $('#tblbmcmilkdispatch-union_code').val();
    var fat = $('#tblbmcmilkdispatchtxn-fat').val();
    var snf = $('#tblbmcmilkdispatchtxn-snf').val();
    var clr = $('#tblbmcmilkdispatchtxn-clr').val();
    var is_clr_input = $('#is_clr_input').val();
    var bmcCode = $('#tblbmcmilkdispatch-bmc_code').val();

    is_clr_input == 0 && (fat == '' || snf == '') && $('#tblbmcmilkdispatchtxn-clr').val('');
    is_clr_input == 1 && (fat == '' || clr == '') && $('#tblbmcmilkdispatchtxn-snf').val('');

    if(setData(bmcCode) && ((is_clr_input == 0 && setData(fat) && setData(snf)) || (is_clr_input ==1 && setData(fat) && setData(clr)))){
        $.ajax({
            type: 'post',
            url:'" . Url::to(['calculate-clr']) . "',
            data: {'union_code':union,'fat':fat,'snf':snf,'clr':clr,'is_clr_input':is_clr_input,'orgCode':bmcCode,'orgType':'BMC','processName':'BMC_DISPATCH_CONFIG'},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success') {
                    if(is_clr_input==0){
                        $('#tblbmcmilkdispatchtxn-clr').val('');
                        $('#tblbmcmilkdispatchtxn-clr').val(obj.data);
                    }else{
                        $('#tblbmcmilkdispatchtxn-snf').val('');
                        $('#tblbmcmilkdispatchtxn-snf').val(obj.data);
                    }
                }
            },
            error:function(data){
            }
        });
    }    
};

$('#tblbmcmilkdispatch-bmc_code').change(function() {
    $('#tblbmcmilkdispatchtxn-physical_stock_only').val('');
    isClrInput();
    setDatePurchaseDetails();
    checkQualityRanges();
});


$(document).on('change', '#tblbmcmilkdispatchtxn-milk_type_code', function() {
    checkQualityRanges();
});

function checkQualityRanges(){
    var union = $('#tblbmcmilkdispatch-union_code').val();
    var bmcCode = $('#tblbmcmilkdispatch-bmc_code').val();
    var milkTypeCode = $('#tblbmcmilkdispatchtxn-milk_type_code').val();
    if(setData(milkTypeCode) && setData(bmcCode)){
        $.ajax({
            type: 'post',
            url:'" . Url::to(['get-quality-param-range']) . "',
            data: {'union':union,'orgCode':bmcCode,'milkTypeCode':milkTypeCode,'orgType':'BMC','processName':'BMC_MILK_DISPATCH'},
            success: function(data) {  
                var obj = $.parseJSON(data);
                if (obj.status == 'success') {
                    var range = obj.data;
                    var minFat = parseFloat(range.min_fat);
                    var maxFat = parseFloat(range.max_fat);
                    var minSnf = parseFloat(range.min_snf);
                    var maxSnf = parseFloat(range.max_snf);
                    var minClr = parseFloat(range.min_clr);
                    var maxClr = parseFloat(range.max_clr);
                    function showError(fieldName, min, max) {
                        var msg = fieldName + ' should be between ' + min + ' and ' + max;
                        bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                    }
                    $(document).off('change', '#tblbmcmilkdispatchtxn-fat, #tblbmcmilkdispatchtxn-snf, #tblbmcmilkdispatchtxn-clr').on('change', '#tblbmcmilkdispatchtxn-fat, #tblbmcmilkdispatchtxn-snf, #tblbmcmilkdispatchtxn-clr', function () {
                        var fat = parseFloat($('#tblbmcmilkdispatchtxn-fat').val());
                        var snf = parseFloat($('#tblbmcmilkdispatchtxn-snf').val());
                        var clr = parseFloat($('#tblbmcmilkdispatchtxn-clr').val());

                        if (!isNaN(fat) && (fat < minFat || fat > maxFat)) {
                            showError('FAT', minFat, maxFat);
                            $('#tblbmcmilkdispatchtxn-fat').val('');
                        }
                        if (!$('#tblbmcmilkdispatchtxn-snf').is('[readonly]')) {
                            var snf = parseFloat($('#tblbmcmilkdispatchtxn-snf').val());
                            if (!isNaN(snf) && (snf < minSnf || snf > maxSnf)) {
                                showError('SNF', minSnf, maxSnf);
                                $('#tblbmcmilkdispatchtxn-snf').val('');
                            }
                        }
                        if (!$('#tblbmcmilkdispatchtxn-clr').is('[readonly]')) {
                            var clr = parseFloat($('#tblbmcmilkdispatchtxn-clr').val());
                            if (!isNaN(clr) && (clr < minClr || clr > maxClr)) {
                                showError('CLR', minClr, maxClr);
                                $('#tblbmcmilkdispatchtxn-clr').val('');
                            }
                        }
                    });
                }
            },
            error:function(data){
            }
        });
    }    
};

function setDatePurchaseDetails(){
    $('.field-tblbmcmilkdispatch-from_date').addClass('disabled no_pointer');
    $('.field-tblbmcmilkdispatch-from_shift_code').addClass('no_pointer_disabled');
    $('.field-tblbmcmilkdispatch-to_date').addClass('disabled no_pointer');
    $('.field-tblbmcmilkdispatch-to_shift_code').addClass('no_pointer_disabled');
    var bmcCode = $('#tblbmcmilkdispatch-bmc_code').val();
    if(setData(bmcCode)){
        $.ajax({
            type: 'post',
            url:'" . Url::to(['get-date-purchase-details']) . "',
            data: {'bmcCode':bmcCode},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.data.status == 'success') {
                    $('#tblbmcmilkdispatch-from_date').val(obj.data.from_date).trigger('change');
                    $('#tblbmcmilkdispatch-from_shift_code').val(obj.data.from_shift).trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatch-to_date').val(obj.data.to_date).trigger('change');
                    $('#tblbmcmilkdispatch-to_shift_code').val(obj.data.to_shift).trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatchtxn-physical_stock_only').val(obj.data.physical_stock_only);
                    $('#purchase-detial').html(obj.result);
                } else {
                    resetFields();
                }
            }
        });
    } else {
        resetFields();
    }
};

function resetFields() {
    $('#tblbmcmilkdispatch-from_date').val('').change();
    $('#tblbmcmilkdispatch-to_date').val('').change();
    $('#tblbmcmilkdispatch-from_shift_code').val('').trigger('change');
    $('#tblbmcmilkdispatch-to_shift_code').val('').trigger('change');
    $('#purchase-detial table tbody').html('');
}

function isClrInput(){
    var union = $('#tblbmcmilkdispatch-union_code').val();
    var bmcCode = $('#tblbmcmilkdispatch-bmc_code').val();
    $('#tblbmcmilkdispatchtxn-is_clr_input').val('');
    if(setData(bmcCode)){
        $.ajax({
            type: 'post',
            url:'" . Url::to(['get-clr-input']) . "',
            data: {'union_code':union,'orgCode':bmcCode,'field':'BMC','for':'BMC_DISPATCH_CONFIG'},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success' && obj.data != null) {
                    var is_clr_input = obj.data;
                    $('#is_clr_input').val(is_clr_input);
                    $('#tblbmcmilkdispatchtxn-is_clr_input').val(is_clr_input);
                    if (is_clr_input == 0) {
                        $('#tblbmcmilkdispatchtxn-snf').attr('readonly', false);
                        $('#tblbmcmilkdispatchtxn-clr').attr('readonly', true);
                    } else {
                        $('#tblbmcmilkdispatchtxn-snf').attr('readonly', true);
                        $('#tblbmcmilkdispatchtxn-clr').attr('readonly', false);
                    }
                }
            }
        });
    }
};

$(document).on('change','#tblbmcmilkdispatchtxn-dispatch_qty,#tblbmcmilkdispatchtxn-rtpl', function() {
    var rtpl=$('#tblbmcmilkdispatchtxn-rtpl').val();
    var dispatch_qty=$('#tblbmcmilkdispatchtxn-dispatch_qty').val();
    if(setData(rtpl) && setData(dispatch_qty)){
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

$(document).on('click','.edit-record',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    editTransaction(id);
});

function editTransaction(bmc_milk_dispatch_txn_code){
    if(setData(bmc_milk_dispatch_txn_code)){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['update-transaction']) . "',
            data: {'bmc_milk_dispatch_txn_code' : bmc_milk_dispatch_txn_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                if(data.status == 'success'){
                    $.each(data.modelData, function(index, value) {
                        $('#tblbmcmilkdispatchtxn-'+index).val(value);
                    });
                    var cnt = 1;
                    $.each(data.configData, function(index, value) {
                        $('#tblconfigtxnresult-' + cnt + '-config_code').val(index);
                        var resultField = $('#tblconfigtxnresult-' + cnt + '-config_result');
                        var type = resultField.find('input').attr('type');
                        if (type == 'radio') {
                            resultField.find('input[type=\"radio\"][value=\"' + value + '\"]').prop('checked', true);
                        } else if (resultField.is(':checkbox')) {
                            resultField.prop('checked', value === '1');
                        } else {
                            resultField.val(value);
                        }
                        cnt++;
                    });
                    $('#tblbmcmilkdispatchtxn-bmc_milk_dispatch_txn_code').val(data.modelData.bmc_milk_dispatch_txn_code);
                    $('#tblbmcmilkdispatchtxn-original_dispatch_qty').val(data.modelData.dispatch_qty);
                    $('#tblbmcmilkdispatchtxn-milk_type_code').trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatchtxn-milk_quality_type_code').trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatchtxn-bmc_silos_info_code').trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatchtxn-chamber_no').trigger('change').trigger('select2:select');
                    $('#tblbmcmilkdispatchtxn-qty_diff_type_code').trigger('change').trigger('select2:select');
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                    $(window).scrollTop(0);
                } else {
                    $('#tblbmcmilkdispatchtxn-original_dispatch_qty').val('0');
                }
            },
        });
    }
};

";

$script .= "
    var isTxnEditable = " . json_encode($txnEdit) . ";
    $(document).off('change', '.filldata').on('change', '.filldata', function () {
        var bmc_code = $('#tblbmcmilkdispatch-bmc_code').val();
        var union_code = $('#tblbmcmilkdispatch-union_code').val();
        var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
        $('#transactions-from').html('');
        $('#transactions-detial').html('');           
        BindData(bmc_code,bmc_milk_dispatch_code,union_code);            
    });
      
    function BindData(bmc_code,bmc_milk_dispatch_code,union_code){
        if(setData(bmc_code) && !isTransactionFormLoad){
            $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'process_name':'BMC_DISPATCH','org_type':'BMC','org_code':bmc_code,'union_code':union_code},             
                success: function(data) {
                    if(isSecondTransaction){
                        isTransactionFormLoad = true;
                    }
                    $('#transactions-from').html(data);                                                                 
                }
            });
        }
        if(setData(bmc_milk_dispatch_code) && !isTransactionDetailLoad) {
            $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-detail']) . "',
                data: {'bmc_milk_dispatch_code' : bmc_milk_dispatch_code, 'txnEdit': isTxnEditable},             
                success: function(data) {
                    if(isSecondTransaction){
                        isTransactionDetailLoad = true;
                    }
                    $('#transactions-detial').html(data);
                },
                error: function(data) {  
                }
            });    
        } 
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
        $(document).on('change', '#tblbmcmilkdispatch-transaction_date, #tblbmcmilkdispatch-to_date', function() {
            var to_date = $('#tblbmcmilkdispatch-to_date').val();
            var transactionDate = $('#tblbmcmilkdispatch-transaction_date').val();
            if (setData(transactionDate) && setData(to_date)) {
                var toParts = to_date.split('-');
                var txnParts = transactionDate.split('-');
                var toDateObj = new Date(toParts[2], toParts[1] - 1, toParts[0]);
                var txnDateObj = transactionDate ? new Date(txnParts[2], txnParts[1] - 1, txnParts[0]) : null;
                if (setData(txnDateObj) && txnDateObj < toDateObj) {
                    var errorMessage = 'must not be less than to date.';
                    var errorElement = '<div class=\"error-message error_message\">' + errorMessage + '</div>';
                    $('.field-tblbmcmilkdispatch-transaction_date.error-message').remove();
                    $('.field-tblbmcmilkdispatch-transaction_date').append(errorElement);
                } else {
                    $('.field-tblbmcmilkdispatch-transaction_date .error-message').remove();
                }
            } else {
                $('.field-tblbmcmilkdispatch-transaction_date .error-message').remove();
            }
        });
    });";
    $this->registerJs($script, View::POS_END, 'to-date-from-date');
}
?>