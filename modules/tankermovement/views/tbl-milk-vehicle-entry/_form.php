
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disabled = empty($model->milk_vehicle_entry_code) ? '' : 'disabled';
$milk_vehicle_entry_code = $model->milk_vehicle_entry_code;
$tripMandateOnReceipt = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'trip_mandate_on_receipt', 'PORTAL') == 1 ? TRUE : FALSE;
?>
<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'milk-vehicle-form'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary([$model, $txn_model]); ?>

<?= Html::activeHiddenInput($model, 'milk_vehicle_entry_code'); ?>
<?= Html::hiddenInput('entry_type', '', ['id' => 'entry_type']); ?>
<div class="micro_form">
    <div class="row">
        <div class="col-lg-10 master_fields <?= $disabled ?>">
            <?php echo Html::hiddenInput('is_clr_input', 0, ['id' => 'is_clr_input']); ?>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'receipt_at'); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::hiddenInput('partyTypeDest', 'milkReceiptDest', ['id' => 'partyTypeDest']); ?>
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilkvehicleentry-receipt_at,tblmilkvehicleentry-union_code,NULL,NULL,partyTypeDest', 'receipt_at_code', $model->getAttributeLabel('receipt_at_code'), FALSE, $readonly); ?>
            </div>
            <div class="col-sm-2 ReceiptDatetime">
                <?= Yii::$app->controls->date($model, $form, 'receipt_datetime', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'receipt_shift_code'); ?>
            </div>
            <?= Html::hiddenInput('tankerMovement', 'falseRLS', ['id' => 'tankerMovement']); ?>
            <?= Html::hiddenInput('partyTypeSource', 'milkReceiptSource', ['id' => 'partyTypeSource']); ?>
            <?php if (!$tripMandateOnReceipt) { ?>
                <div class="col-sm-1">
                    <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'dispatch_from'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilkvehicleentry-dispatch_from,tblmilkvehicleentry-union_code,NULL,tankerMovement,partyTypeSource', 'dispatch_from_code', $model->getAttributeLabel('dispatch_from_code'), FALSE, $readonly); ?>
                </div>
            <?php } ?>
            <div class="col-sm-2 tanker_no_hide"> 
                <?= $form->field($model, 'tanker_no')->textInput() ?>
            </div>
            <?= Html::hiddenInput('trip_type', 'receipt', ['id' => 'trip_type']); ?>
            <div class="col-sm-2 disabled vehicle_code_hide"> 
                <?= Yii::$app->dropdown->vehicleMasterOpen($model, $form, 'tblmilkvehicleentry-receipt_at_code,tblmilkvehicleentry-union_code,tblmilkvehicleentry-receipt_at,trip_type', 'vehicle_code', $model->getAttributeLabel('vehicle_code'), false, '', $readonly); ?>
            </div>
            <div class="col-sm-2 filldata trip-code-hide">
                <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'tblmilkvehicleentry-vehicle_code,trip_type,tblmilkvehicleentry-receipt_datetime,trip_code', 'trip_code', $model->getAttributeLabel('trip_code'), false, false); ?>
            </div>
            <?php if ($tripMandateOnReceipt) { ?>
                <div class="col-sm-1">
                    <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'dispatch_from'); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilkvehicleentry-dispatch_from,tblmilkvehicleentry-union_code,NULL,tankerMovement,partyTypeSource', 'dispatch_from_code', $model->getAttributeLabel('dispatch_from_code'), FALSE, $readonly); ?>
                </div>
            <?php } ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'arrival_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?> 
            </div>
            <div class="col-sm-2 number-validate disp_none"> 
                <?= $form->field($model, 'gross_weight')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-2 number-validate disp_none"> 
                <?= $form->field($model, 'tare_weight')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-2 number-validate disp_none"> 
                <?= $form->field($model, 'qty')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-2 disp_none">
                <?= $form->field($model, 'tare_weight_time')->widget(MaskedInput::className(), ['mask' => '99:99', 'options' => ['readonly' => true]]); ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div id="dispatch-detail">
                <h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Summary') ?></h5>
                <table class="table tab-bordered">
                    <thead>
                        <tr>
                            <th>Challan No.</th>
                            <th>From Date</th>
                            <th>From Shift</th>   
                            <th>To Date</th>
                            <th>To Shift</th>                   
                            <th>Vehicle No.</th>    
                            <th>Dispatch Qty.</th>  
                            <th>Fat.</th>  
                            <th>Snf.</th>                 
                            <th>In Time</th>                   
                            <th>Out Time</th>
                            <th>Action</th>     
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="milk-receipt-transaction">
            <div class="col-lg-12 mt35">
                <h5 class="panel-heading mb15"><?= Yii::t('app', 'Milk Receipt Transaction') ?></h5>
                <div class="col-sm-1 entry_type"> 
                    <?= Yii::$app->dropdown->dropdownStatic('entry_type', $txn_model, $form, 'form-group', $txn_model->getAttributeLabel('entry_type'), false, 'entry_type', false); ?>
                </div>
                <div class='reset_field'>
                    <div class="col-sm-2 type_hide"> 
                        <?= Yii::$app->dropdown->depend_dropdown('trip_challan', $txn_model, $form, 'tblmilkvehicleentry-trip_code', 'form-group', $txn_model->getAttributeLabel('challan_no'), '', FALSE); ?>
                    </div>
                    <div class="col-sm-2 type_hide disabled">
                        <?= $form->field($txn_model, 'source_org_type')->textInput() ?>
                    </div>
                    <div class="col-sm-2 type_hide disabled">
                        <?= $form->field($txn_model, 'source')->textInput() ?>
                        <?= $form->field($txn_model, 'source_org_code')->hiddenInput()->label(FALSE) ?>
                    </div>
                    <div class="col-sm-2 type_hide disabled">
                        <?= $form->field($txn_model, 'destination_type')->textInput() ?>
                    </div>
                    <div class="col-sm-2 type_hide disabled">
                        <?= $form->field($txn_model, 'destination')->textInput() ?>
                        <?= $form->field($txn_model, 'destination_code')->hiddenInput()->label(FALSE) ?>
                    </div>
                    <div class="col-sm-1"> 
                        <?= Yii::$app->dropdown->dropdown('milk_type_code', $txn_model, $form, '', true, FALSE, 'milk_type_code'); ?>
                    </div>
                    <div class="col-sm-1"> 
                        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $txn_model, $form, '', true, FALSE, 'milk_quality_type_code'); ?>
                    </div>
                    <div class="col-sm-1 chamber_no_hide"> 
                        <?= Yii::$app->dropdown->chamberNoList($txn_model, $form, 'tblmilkvehicleentry-vehicle_code', 'chamber_no', Yii::t('app', 'Chamber No')); ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'gross_weight')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'tare_weight')->textInput() ?>
                    </div>
                    <div class="col-sm-1">
                        <?= $form->field($txn_model, 'gross_weight_time')->widget(MaskedInput::className(), ['mask' => '99:99']); ?>
                    </div>
                    <div class="col-sm-1">
                        <?= $form->field($txn_model, 'tare_weight_time')->widget(MaskedInput::className(), ['mask' => '99:99']); ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'chamber_quantity')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'fat')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'snf')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'clr')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'water')->textInput() ?>
                    </div>
                    <div class="col-sm-1 number-validate"> 
                        <?= $form->field($txn_model, 'temp')->textInput() ?>
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
                        <?= $form->field($txn_model, 'mbrt')->textInput() ?>
                    </div>
                    <div class="col-sm-1"> 
                        <?= Html::activeHiddenInput($txn_model, 'milk_vehicle_entry_transaction_code'); ?>
                        <?= Html::activeHiddenInput($txn_model, 'is_qty_only'); ?>
                        <?= Html::activeHiddenInput($txn_model, 'is_pending_merge'); ?>
                        <?= Html::activeHiddenInput($txn_model, 'is_clr_input'); ?>
                        <?= $form->field($txn_model, 'acidity')->textInput() ?>
                    </div>                
                </div>
                <div id="transactions-from">             
                </div>
            </div>
            <div class="col-sm-12 shortcut-main mt15" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Save'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['create']),
                            'beforeSend' => new JsExpression('function(data){
                                                    $("#loadercontent").show();
                                                    $("#pageloader").show();
                                                    }'),
                            'success' => new JsExpression('function(data){
                                                                    var data=$.parseJSON(data);
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    if (data.status == "success"){ 
                                                                        $("#loadercontent").hide();
                                                                        $("#pageloader").hide();
                                                                        $(".number-validate.disp_none, .disp_none").removeClass("disp_none");
                                                                        $(".field-tblmilkvehicleentry-tare_weight_time").parent().removeClass("disp_none");
                                                                        $("#tblmilkvehicleentry-milk_vehicle_entry_code").val(data.milk_vehicle_entry_code);
                                                                        $("#tblmilkvehicleentry-gross_weight").val(data.gross_weight);
                                                                        $("#tblmilkvehicleentry-tare_weight").val(data.tare_weight);
                                                                        $("#tblmilkvehicleentry-tare_weight_time").val(data.tare_weight_time);
                                                                        $("#tblmilkvehicleentry-tare_weight").trigger("change");
                                                                        $(".help-block").text("");
                                                                        $(".form-group").removeClass("has-error");         
                                                                        $(".error-summary").hide();
                                                                        $(".error-summary li").remove();
                                                                        reloadGrid();
                                                                        $(".master_fields input, .master_fields select, .master_fields textarea").prop("disabled", true);
                                                                        $(".entry_type").addClass("disabled");
                                                                        $("#entry_type").val($("#tblmilkvehicleentrytransaction-entry_type" ).val());
                                                                        $("#tblmilkvehicleentrytransaction-entry_type" ).prop("disabled", true);
                                                                        $("#milk-vehicle-form .reset_field input").val("");                                                                    
                                                                        $("#transactions-from input[type=radio]").prop("checked",true);
                                                                        $("#transactions-from input[type=text]").val("");
                                                                        $("#transactions-from select").val("");
                                                                        $("#milk-vehicle-form .reset_field select").val("");
                                                                        $("#milk-vehicle-form .reset_field textarea").val("");
                                                                        $("#tblmilkvehicleentrytransaction-challan_no").change();
                                                                        $("#tblmilkvehicleentrytransaction-milk_type_code").change();
                                                                        $("#tblmilkvehicleentrytransaction-milk_quality_type_code").change();
                                                                        $("#tblmilkvehicleentrytransaction-chamber_no").change();
                                                                        // Set current time
                                                                        let now = new Date();
                                                                        let h = String(now.getHours()).padStart(2, "0");
                                                                        let m = String(now.getMinutes()).padStart(2, "0");
                                                                        let currentTime = h + ":" + m;
                                                                        $("#tblmilkvehicleentrytransaction-gross_weight_time").val(currentTime);
                                                                        $("#tblmilkvehicleentrytransaction-tare_weight_time").val(currentTime);
                                                                        if($("#tblmilkvehicleentry-receipt_at").val() == "PLANT"){
                                                                            tripSubStatus();
                                                                        }   
                                                                        $(".panel-body").scrollTop(0);                                                                    
                                                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                    }else{                                                             
                                                                        $("#loadercontent").hide();
                                                                        $("#pageloader").hide();
                                                                        $(".help-block").text("");
                                                                        $(".form-group").removeClass("has-error");
                                                                        $(".error-summary").hide();
                                                                        $(".error-summary li").remove();
                                                                        $.each(data, function(key, val) {
                                                                            $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        });
                                                                        $(".error-summary").show();
                                                                    }
                                                    }'),
                        ],
                        'options' => ['class' => 'btn btn-default btn-raised',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="col-lg-12">
        <div id="milk-receipt-transaction-detail">
            <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
                <?=
                $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
if ($bmc_user == 'BMC'):
    $script = "
        function removeOptions(selectBox) {
            var options = selectBox.querySelectorAll('option');
            for (var i = 1; i < options.length; i++) {
                if (options[i].value !== 'BMC' && options[i].value !== 'PARTY') {
                    options[i].remove();
                }
            }
        }
        var selectBox1 = document.getElementById('tblmilkvehicleentry-dispatch_from');
        removeOptions(selectBox1);
        var selectBox2 = document.getElementById('tblmilkvehicleentry-receipt_at');
        removeOptions(selectBox2);
    ";
    $this->registerJs($script, View::POS_END, 'for-bmc-user');
endif;
?>

<?php
$tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'tanker_movement_with_trip_sub_status', 'PORTAL') == 1 ? TRUE : FALSE;
$script = "
    var org_code = '';
    var qltyParamsReadOnly = false;
    var isTripStatusAlertShown = false;
    $('#dispatch-detail').css('display', 'none');
    $('#milk-receipt-transaction').css('display', 'none');
    $('#milk-receipt-transaction-detail').css('display', 'none');
    $('.tanker_no_hide').css('display', 'none');
    $('#tblmilkvehicleentry-vehicle_code, #tblmilkvehicleentry-receipt_datetime').on('change', function() {
        var vehicleCode = $('#tblmilkvehicleentry-vehicle_code').val();
        var ReceiptDatetime = $('#tblmilkvehicleentry-receipt_datetime').val();
        if (setData(vehicleCode) && setData(ReceiptDatetime)) {
            setTimeout(function() {
                var tripCodeDropdownLength = $('#tblmilkvehicleentry-trip_code option').length - 1;
                if (tripCodeDropdownLength > 0) {
                    $('.trip-code-hide').css('display', 'block');
                }
                else {
                    $('.trip-code-hide').css('display', 'none');
                }
            }, 1000);
        }
        else {
            $('.trip-code-hide').css('display', 'none');
        }
    });
    
    $(document).on('change', '#tblmilkvehicleentry-receipt_at, #tblmilkvehicleentry-dispatch_from, #tblmilkvehicleentry-receipt_at_code', function() {
        var receipt_at = $('#tblmilkvehicleentry-receipt_at').val();
        var dispatch_from = $('#tblmilkvehicleentry-dispatch_from').val();
        
        var entryTypeField = $('#tblmilkvehicleentrytransaction-entry_type');
        var EntryType = document.querySelector('.col-sm-1.entry_type');
        var tripMandateOnReceipt = '" . $tripMandateOnReceipt . "';
        var tankerMovementWithTripSubStatus = '" . $tankerMovementWithTripSubStatus . "';
        if (setData(receipt_at)) {
            $('#tblmilkvehicleentrytransaction-is_clr_input').val('');
            if (setData(dispatch_from) && dispatch_from == 'PARTY' && tripMandateOnReceipt == false) {
               $('.tanker_no_hide').css('display', 'block');
               $('.vehicle_code_hide').css('display', 'none');
               $('.chamber_no_hide').css('display', 'none');
               $('#dispatch-detail').css('display', 'none');
               $('#milk-receipt-transaction').css('display', 'block');
               $('#milk-receipt-transaction-detail').css('display', 'block');
               $('.trip-code-hide').css('display', 'none');
               entryTypeField.val('CONSOLIDATED').prop('readonly', true).trigger('change');
               $('#tblmilkvehicleentry-trip_code').val('').trigger('change').trigger('select2:select');
               $('#tblmilkvehicleentry-vehicle_code').val('').trigger('change').trigger('select2:select');
               EntryType.classList.add('no_pointer_disabled');
            } else if (receipt_at == 'PLANT') {
                $('.vehicle_code_hide').css('display', 'block');
                $('.tanker_no_hide').css('display', 'none');
                $('.trip-code-hide').css('display', 'block');
                entryTypeField.val('').prop('readonly', false).trigger('change');   
                EntryType.classList.remove('no_pointer');    
                $('#tblmilkvehicleentry-tanker_no').val('').trigger('change');
                isClrInput();
                $(document).off('change', '#tblmilkvehicleentry-trip_code, #tblmilkvehicleentry-union_code, #tblmilkvehicleentry-receipt_at_code,#tblmilkvehicleentry-dispatch_from')
                            .on('change', '#tblmilkvehicleentry-trip_code, #tblmilkvehicleentry-union_code, #tblmilkvehicleentry-receipt_at_code,#tblmilkvehicleentry-dispatch_from', tripSubStatus);
            } else {
                $('#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-water, #tblmilkvehicleentrytransaction-temp, #tblmilkvehicleentrytransaction-protein, #tblmilkvehicleentrytransaction-density, #tblmilkvehicleentrytransaction-lactose, #tblmilkvehicleentrytransaction-freezing_point, #tblmilkvehicleentrytransaction-mbrt, #tblmilkvehicleentrytransaction-acidity').val('').prop('readonly', false);
                $('#tblmilkvehicleentrytransaction-is_qty_only, #tblmilkvehicleentrytransaction-is_pending_merge').val('0');
                $('#dispatch-detail').css('display', 'block');
                $('#milk-receipt-transaction').css('display', 'block');
                $('#milk-receipt-transaction-detail').css('display', 'block');
            }
        } else {
            $('#dispatch-detail').css('display', 'none');
            $('#milk-receipt-transaction').css('display', 'none');
            $('#milk-receipt-transaction-detail').css('display', 'none');
        }
    });
    
    function tripSubStatus() {
        var trip_code = $('#tblmilkvehicleentry-trip_code').val();
        var union_code = $('#tblmilkvehicleentry-union_code').val();  
        var receiptAtCode = $('#tblmilkvehicleentry-receipt_at_code').val();  
        var tankerMovementWithTripSubStatus = '" . $tankerMovementWithTripSubStatus . "';
        var receipt_at = $('#tblmilkvehicleentry-receipt_at').val();
        var dispatch_from = $('#tblmilkvehicleentry-dispatch_from').val();
        var tripMandateOnReceipt = '" . $tripMandateOnReceipt . "';
        if(setData(trip_code) && setData(union_code) && setData(receiptAtCode) && receipt_at == 'PLANT'){
            if(tankerMovementWithTripSubStatus){
                    qltyParamsReadOnly = false;
                    $.ajax({
                    type: 'post',
                    url: '" . Url::to(['trip-sub-status']) . "',
                    data: {'trip_code' : trip_code,'union_code':union_code,'receipt_at_code' : receiptAtCode}, 
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success'){
                            $('#dispatch-detail').css('display', 'block');
                            $('#milk-receipt-transaction').css('display', 'block');
                            $('#milk-receipt-transaction-detail').css('display', 'block');
                            // $(document).on('change','#tblmilkvehicleentrytransaction-chamber_no', function() {
                                    // var chamber_no = $('#tblmilkvehicleentrytransaction-chamber_no').val();
                                    // if (setData(chamber_no) && obj1.record_data.hasOwnProperty(chamber_no) ) {
                                    //     $('#tblmilkvehicleentrytransaction-fat').val(obj1.record_data[chamber_no].fat);
                                    //     $('#tblmilkvehicleentrytransaction-snf').val(obj1.record_data[chamber_no].snf);
                                    //     $('#tblmilkvehicleentrytransaction-clr').val(obj1.record_data[chamber_no].clr);
                                    //     $('#tblmilkvehicleentrytransaction-water').val(obj1.record_data[chamber_no].water);
                                    //     $('#tblmilkvehicleentrytransaction-temp').val(obj1.record_data[chamber_no].temp);
                                    //     $('#tblmilkvehicleentrytransaction-protein').val(obj1.record_data[chamber_no].protein);
                                    //     $('#tblmilkvehicleentrytransaction-density').val(obj1.record_data[chamber_no].density);
                                    //     $('#tblmilkvehicleentrytransaction-lactose').val(obj1.record_data[chamber_no].lactose);
                                    //     $('#tblmilkvehicleentrytransaction-freezing_point').val(obj1.record_data[chamber_no].freezing_point);
                                    //     $('#tblmilkvehicleentrytransaction-mbrt').val(obj1.record_data[chamber_no].mbrt);
                                    //     $('#tblmilkvehicleentrytransaction-acidity').val(obj1.record_data[chamber_no].acidity);
                                    // } else {
                                            $('#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-water, #tblmilkvehicleentrytransaction-temp, #tblmilkvehicleentrytransaction-protein, #tblmilkvehicleentrytransaction-density, #tblmilkvehicleentrytransaction-lactose, #tblmilkvehicleentrytransaction-freezing_point, #tblmilkvehicleentrytransaction-mbrt, #tblmilkvehicleentrytransaction-acidity').val('0').prop('readonly', true);
                                            $('#tblmilkvehicleentrytransaction-is_qty_only, #tblmilkvehicleentrytransaction-is_pending_merge').val('1');
                                            qltyParamsReadOnly = true;
                                    // }
                            // });
                            $(document).off('change', '#tblmilkvehicleentrytransaction-chamber_no')
                                .on('change', '#tblmilkvehicleentrytransaction-chamber_no', function () {
                                    validateWeightTimes(obj1.lotQltyData);
                                });

                            $(document).off('change', '#tblmilkvehicleentrytransaction-gross_weight_time')
                                .on('change', '#tblmilkvehicleentrytransaction-gross_weight_time', function () {
                                    validateWeightTimes(obj1.lotQltyData);
                                });

                            $(document).off('change', '#tblmilkvehicleentrytransaction-tare_weight_time')
                                .on('change', '#tblmilkvehicleentrytransaction-tare_weight_time', function () {
                                    validateWeightTimes(obj1.lotQltyData);
                                });
                        } else if(obj1.validation){
                            if(!isTripStatusAlertShown) {
                                isTripStatusAlertShown = true;
                                bootbox.alert(\"<div class='row'><div class='col-sm-12'><div class='bg-danger'><i class='fa fa-times'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                $('.bootbox').on('hidden.bs.modal', function() {
                                    isTripStatusAlertShown = false;
                                });
                            }
                        } else {
                            $('#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-water, #tblmilkvehicleentrytransaction-temp, #tblmilkvehicleentrytransaction-protein, #tblmilkvehicleentrytransaction-density, #tblmilkvehicleentrytransaction-lactose, #tblmilkvehicleentrytransaction-freezing_point, #tblmilkvehicleentrytransaction-mbrt, #tblmilkvehicleentrytransaction-acidity').val('').prop('readonly', false);
                            $('#tblmilkvehicleentrytransaction-is_qty_only, #tblmilkvehicleentrytransaction-is_pending_merge').val('0');
                            $('#dispatch-detail').css('display', 'block');
                            $('#milk-receipt-transaction').css('display', 'block');
                            $('#milk-receipt-transaction-detail').css('display', 'block');
                            isClrInput();
                        }
                    },
                });
            } else {
                $('#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-water, #tblmilkvehicleentrytransaction-temp, #tblmilkvehicleentrytransaction-protein, #tblmilkvehicleentrytransaction-density, #tblmilkvehicleentrytransaction-lactose, #tblmilkvehicleentrytransaction-freezing_point, #tblmilkvehicleentrytransaction-mbrt, #tblmilkvehicleentrytransaction-acidity').val('').prop('readonly', false);
                $('#tblmilkvehicleentrytransaction-is_qty_only, #tblmilkvehicleentrytransaction-is_pending_merge').val('0');
                $('#dispatch-detail').css('display', 'block');
                $('#milk-receipt-transaction').css('display', 'block');
                $('#milk-receipt-transaction-detail').css('display', 'block');
                isClrInput();
            }
        } else if (setData(dispatch_from) && dispatch_from == 'PARTY' && tripMandateOnReceipt == false) {
        } else if(receipt_at != 'PARTY'){
            $('#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-water, #tblmilkvehicleentrytransaction-temp, #tblmilkvehicleentrytransaction-protein, #tblmilkvehicleentrytransaction-density, #tblmilkvehicleentrytransaction-lactose, #tblmilkvehicleentrytransaction-freezing_point, #tblmilkvehicleentrytransaction-mbrt, #tblmilkvehicleentrytransaction-acidity').val('').prop('readonly', false);
            $('#dispatch-detail').css('display', 'none');
            $('#milk-receipt-transaction').css('display', 'none');
            $('#milk-receipt-transaction-detail').css('display', 'none');
            isClrInput();
        }
    }
        
    function validateWeightTimes(lotQltyData) {
        var chamber_no = $('#tblmilkvehicleentrytransaction-chamber_no').val();

        if (setData(chamber_no)) {
            var selectedData = lotQltyData.find(function(item) {
                    return item.chamber_no == chamber_no;
                });
        }
        if (setData(selectedData)) {
            var sampleDate = new Date(selectedData.sample_datetime);

            var today = new Date();
            var dateStr = today.getFullYear() + '-' +
                        String(today.getMonth() + 1).padStart(2, '0') + '-' +
                        String(today.getDate()).padStart(2, '0');

            var grossTime = $('#tblmilkvehicleentrytransaction-gross_weight_time').val();
            var tareTime = $('#tblmilkvehicleentrytransaction-tare_weight_time').val();

            if (grossTime && grossTime.trim() !== '') {
                var grossDateTime = new Date(dateStr + 'T' + grossTime + ':00');
                if (grossDateTime > sampleDate) {
                    var msg = 'Gross Weight Time must be before Sample DateTime.'; 
                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                    $('#tblmilkvehicleentrytransaction-gross_weight_time').val('');
                }
            }

            if (tareTime && tareTime.trim() !== '') {
                var tareDateTime = new Date(dateStr + 'T' + tareTime + ':00');
                if (tareDateTime < sampleDate) {
                    var msg = 'Tare Weight Time must be after Sample DateTime.'; 
                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                    $('#tblmilkvehicleentrytransaction-tare_weight_time').val('');
                }
            }
        } else {
            var msg = 'No data found for selected Chamber'; 
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
        }
    }
    
    $('.type_hide').hide();
    $(document).on('change','#tblmilkvehicleentrytransaction-entry_type', function() {
    var entry_type=$('#tblmilkvehicleentrytransaction-entry_type').val();
        if(setData(entry_type) && entry_type=='INDIVIDUAL'){
         $('.type_hide').show();
        } else if(setData(entry_type) && entry_type=='CONSOLIDATED'){
            $('.type_hide').hide();
            $('#tblmilkvehicleentrytransaction-source_org_code').val('');
            $('#tblmilkvehicleentrytransaction-source_org_type').val('');
            $('#tblmilkvehicleentrytransaction-source').val('');    
            $('#tblmilkvehicleentrytransaction-destination_code').val('');
            $('#tblmilkvehicleentrytransaction-destination_type').val('');
            $('#tblmilkvehicleentrytransaction-destination').val('');
            $('#tblmilkvehicleentrytransaction-challan_no').val('');
        }
    });
    

    $(document).on('change','#tblmilkvehicleentrytransaction-challan_no', function() {
    var challan_no=$('#tblmilkvehicleentrytransaction-challan_no').val();
    var trip_code=$('#tblmilkvehicleentry-trip_code').val();
        if(setData(challan_no) && setData(trip_code)){
            setSourseDest(challan_no,trip_code);
        } 
    });
    

     $(document).on('change','.filldata', function() {
        var trip_code = $('#tblmilkvehicleentry-trip_code').val();
       if(setData(trip_code)){
            $('#loadercontent').show();
            $('#pageloader').show();
            $('#dispatch-detail').html('');
            $('#transactions-from').html('');
            BindData(trip_code);
         //   GetVehicle(trip_code);
        }      
    });

    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }

    $(document).on('change', '#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-clr, #tblmilkvehicleentrytransaction-snf', function() {
        if(!qltyParamsReadOnly){
            calculateClr();
        }
    });

    function calculateClr(){
        var union = $('#tblmilkvehicleentry-union_code').val();
        var fat = $('#tblmilkvehicleentrytransaction-fat').val();
        var snf = $('#tblmilkvehicleentrytransaction-snf').val();
        var clr = $('#tblmilkvehicleentrytransaction-clr').val();
        var is_clr_input = $('#is_clr_input').val();
        var receiptAt = $('#tblmilkvehicleentry-receipt_at').val();
        var receiptAtCode = $('#tblmilkvehicleentry-receipt_at_code').val();

        is_clr_input == 0 && (fat == '' || snf == '') && $('#tblmilkvehicleentrytransaction-clr').val('');
        is_clr_input == 1 && (fat == '' || clr == '') && $('#tblmilkvehicleentrytransaction-snf').val('');

        if(setData(receiptAtCode) && setData(receiptAt) && receiptAt == 'PLANT' && ((is_clr_input == 0 && setData(fat) && setData(snf)) || (is_clr_input ==1 && setData(fat) && setData(clr)))){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['calculate-clr']) . "',
                data: {'union_code':union,'fat':fat,'snf':snf,'clr':clr,'is_clr_input':is_clr_input,'receiptAtCode':receiptAtCode},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        if(is_clr_input==0){
                            $('#tblmilkvehicleentrytransaction-clr').val(obj.data);
                        }else{
                            $('#tblmilkvehicleentrytransaction-snf').val(obj.data);
                        }
                    }
                },
                error:function(data){
                }
            });
        }    
    };

    $(document).on('change', '#tblmilkvehicleentry-receipt_at_code, #tblmilkvehicleentrytransaction-milk_type_code', function() {
        if(!qltyParamsReadOnly){
            checkQualityRanges();
        }
    });

    function checkQualityRanges(){
        var union = $('#tblmilkvehicleentry-union_code').val();
        var receiptAt = $('#tblmilkvehicleentry-receipt_at').val();
        var receiptAtCode = $('#tblmilkvehicleentry-receipt_at_code').val();
        var milkTypeCode = $('#tblmilkvehicleentrytransaction-milk_type_code').val();

        if(setData(milkTypeCode) && setData(receiptAtCode) && setData(receiptAt) && receiptAt == 'PLANT'){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-quality-param-range']) . "',
                data: {'union':union, 'receiptAtCode':receiptAtCode, 'milkTypeCode':milkTypeCode},
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
                        $(document).off('change', '#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr').on('change', '#tblmilkvehicleentrytransaction-fat, #tblmilkvehicleentrytransaction-snf, #tblmilkvehicleentrytransaction-clr', function () {
                            var fat = parseFloat($('#tblmilkvehicleentrytransaction-fat').val());
                            var snf = parseFloat($('#tblmilkvehicleentrytransaction-snf').val());
                            var clr = parseFloat($('#tblmilkvehicleentrytransaction-clr').val());

                            if (!isNaN(fat) && (fat < minFat || fat > maxFat)) {
                                showError('FAT', minFat, maxFat);
                                $('#tblmilkvehicleentrytransaction-fat').val('');
                            }
                            if (!$('#tblmilkvehicleentrytransaction-snf').is('[readonly]')) {
                                var snf = parseFloat($('#tblmilkvehicleentrytransaction-snf').val());
                                if (!isNaN(snf) && (snf < minSnf || snf > maxSnf)) {
                                    showError('SNF', minSnf, maxSnf);
                                    $('#tblmilkvehicleentrytransaction-snf').val('');
                                }
                            }
                            if (!$('#tblmilkvehicleentrytransaction-clr').is('[readonly]')) {
                                var clr = parseFloat($('#tblmilkvehicleentrytransaction-clr').val());
                                if (!isNaN(clr) && (clr < minClr || clr > maxClr)) {
                                    showError('CLR', minClr, maxClr);
                                    $('#tblmilkvehicleentrytransaction-clr').val('');
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

    function isClrInput(){
        var union = $('#tblmilkvehicleentry-union_code').val();
        var receiptAt = $('#tblmilkvehicleentry-receipt_at').val();
        var receiptAtCode = $('#tblmilkvehicleentry-receipt_at_code').val();
        $('#tblmilkvehicleentrytransaction-is_clr_input').val('');
        if(setData(receiptAtCode) && (!qltyParamsReadOnly)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-clr-input']) . "',
                data: {'union_code':union,'receiptAtCode':receiptAtCode},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success' && obj.data != null) {
                        var is_clr_input = obj.data;
                        $('#is_clr_input').val(is_clr_input);
                        $('#tblmilkvehicleentrytransaction-is_clr_input').val(is_clr_input);
                        if (is_clr_input == 0) {
                            $('#tblmilkvehicleentrytransaction-snf').attr('readonly', false);
                            $('#tblmilkvehicleentrytransaction-clr').attr('readonly', true);
                        } else {
                            $('#tblmilkvehicleentrytransaction-snf').attr('readonly', true);
                            $('#tblmilkvehicleentrytransaction-clr').attr('readonly', false);
                        }
                    }
                }
            });
        }
    };
    
    function BindData(trip_code){
        $.ajax({
                type: 'get',
                url: '" . Url::to(['dispatch-detail']) . "',
                data: {'trip_code' : trip_code},             
                success: function(data) {
                  $('#dispatch-detail').html(data);  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();  
                },
                  error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
          var receipt_at = $('#tblmilkvehicleentry-receipt_at').val();
          var receipt_at_code = $('#tblmilkvehicleentry-receipt_at_code').val();
          var union_code = $('#tblmilkvehicleentry-union_code').val();  
          $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'receipt_at' : receipt_at,'receipt_at_code' : receipt_at_code,'union_code':union_code},             
                success: function(data) {
                  $('#transactions-from').html(data);
                  if(qltyParamsReadOnly){
                    $('#transactions-from input').prop('readonly', true);                                                         
                  }
                }
            });     
    }
    
    function GetVehicle(trip_code){
         $.ajax({
                type: 'get',
                url: '" . Url::to(['vehicle-detail']) . "',
                data: {'trip_code' : trip_code},             
                success: function(data) {
                $('#tblmilkvehicleentry-vehicle_code').val(data.code);
                $('#tblmilkvehicleentry-vehicle').val(data.name);
                    $('#loadercontent').hide();
                    $('#pageloader').hide();  
                },
                  error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
    }
    function setSourseDest(challan_no,trip_code){
         $.ajax({
                type: 'get',
                url: '" . Url::to(['set-fields']) . "',
                data: {'challan_no' : challan_no,'trip_code':trip_code},             
                success: function(data) {
                $('#tblmilkvehicleentrytransaction-source_org_type').val(data.data.source_org_type.toUpperCase());
                $('#tblmilkvehicleentrytransaction-source_org_code').val(data.data.source_org_code);
                $('#tblmilkvehicleentrytransaction-destination_type').val(data.data.destination_type);
                $('#tblmilkvehicleentrytransaction-destination_code').val(data.data.destination_code);
                $('#tblmilkvehicleentrytransaction-source').val(data.source);
                $('#tblmilkvehicleentrytransaction-destination').val(data.dest);
                    $('#loadercontent').hide();
                    $('#pageloader').hide();  
                },
                  error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
    }
    
    function reloadGrid(){
            var url = '" . Url::to(['list-grid']) . "'+ '?' + $('#milk-vehicle-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    
    
    $(document).on('click','.edit-record',function(e){
        var id= $(this).attr('data-val');
        var name = $(this).attr('data-name');
        editTransaction(id);
    });
    
    function editTransaction(milk_vehicle_entry_transaction_code){
            if(setData(milk_vehicle_entry_transaction_code)){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['update-transaction']) . "',
                    data: {'milk_vehicle_entry_transaction_code' : milk_vehicle_entry_transaction_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblmilkvehicleentrytransaction-'+index).val(value);
                        });
                        $.each(data.configData, function(index, value) {
                            $('#tblconfigtxnresult-'+index+'-config_code').val(index);
                            $('#tblconfigtxnresult-'+index+'-config_result').val(value);
                        });
                            $('#tblmilkvehicleentrytransaction-challan_no').trigger('change');
                            $('#tblmilkvehicleentrytransaction-challan_no').trigger('select2:select');
                            $('#tblmilkvehicleentrytransaction-milk_type_code').trigger('change');
                            $('#tblmilkvehicleentrytransaction-milk_type_code').trigger('select2:select');
                            $('#tblmilkvehicleentrytransaction-milk_quality_type_code').trigger('change');
                            $('#tblmilkvehicleentrytransaction-milk_quality_type_code').trigger('select2:select');
                            $('#tblmilkvehicleentrytransaction-chamber_no').trigger('change');
                            $('#tblmilkvehicleentrytransaction-chamber_no').trigger('select2:select');
                            $('#tblmilkvehicleentrytransaction-source').val(data.source);
                            $('#tblmilkvehicleentrytransaction-destination').val(data.dest);
//                         $('#tblmilkvehicleentry-milk_vehicle_entry_code').val(data.modelData.milk_vehicle_entry_code);
//                       $('#maincontent').html(data);
                         $('.create_fields').addClass('disabled');
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);
                    },
                });
            }
    };
    
    $(document).on('change','#tblmilkvehicleentry-gross_weight,#tblmilkvehicleentry-tare_weight', function() {
        var gross_weight=$('#tblmilkvehicleentry-gross_weight').val() || 0;
        var tare_weight=$('#tblmilkvehicleentry-tare_weight').val() || 0;

        var qty = parseFloat(gross_weight) - parseFloat(tare_weight);
        if (!isNaN(qty)) {
            qty = Math.max(0, qty);
        } else {
            qty = 0;
        }
        $('#tblmilkvehicleentry-qty').val((qty).toFixed(2));
    });
    $(document).on('change','#tblmilkvehicleentrytransaction-gross_weight,#tblmilkvehicleentrytransaction-tare_weight', function() {
        var gross_weight=$('#tblmilkvehicleentrytransaction-gross_weight').val() || 0;
        var tare_weight=$('#tblmilkvehicleentrytransaction-tare_weight').val() || 0;
        var qty = parseFloat(gross_weight) - parseFloat(tare_weight);
        var main_gross_weight=$('#tblmilkvehicleentry-gross_weight').val() || 0;
        if (parseFloat(gross_weight) < parseFloat(tare_weight)) {
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>tare weight should not be more than gross weight</span></div></div>\");
            $('#tblmilkvehicleentrytransaction-tare_weight').val('');
            $('#tblmilkvehicleentrytransaction-chamber_quantity').val('');
            return false;
        }

        if (main_gross_weight != 0 && parseFloat(main_gross_weight) < parseFloat(gross_weight)) {
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>gross weight should not be more than first gross weight</span></div></div>\");
            $('#tblmilkvehicleentrytransaction-gross_weight').val('');
            return false;
        }

        if (!isNaN(qty)) {
            qty = Math.max(0, qty);
        } else {
            qty = 0;
        }
        $('#tblmilkvehicleentrytransaction-chamber_quantity').val((qty).toFixed(2));
    });
    
       function SourceData(trip_code){
            $.ajax({
                type: 'get',
                url: '" . Url::to(['vehicle-trip-detail']) . "',
                data: {'trip_code' : trip_code},             
                success: function(data) {
                   var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        var sourceOrgTypeUpper = obj.data.source_org_type.toUpperCase();
                            $('#tblmilkvehicleentry-dispatch_from').val(sourceOrgTypeUpper).trigger('change').trigger('select2:select');
                            var dispatch_from = $('#tblmilkvehicleentry-dispatch_from').val();
                            $('#tblmilkvehicleentry-dispatch_from_code').on('depdrop.afterChange', function(e) {
                            setTimeout(function() {
                                $('#tblmilkvehicleentry-dispatch_from_code').val(obj.data.source_org_code).trigger('change').trigger('select2:select');
                            }, 1000);
                        });
                    }
                },
                  error: function(data) {  
                }
            });
    }
    $(document).on('change','#tblmilkvehicleentry-trip_code', function() {
        var dispatch_from = $('#tblmilkvehicleentry-dispatch_from').val();
        var tripMandateOnReceipt = '" . $tripMandateOnReceipt . "';
        if (setData(dispatch_from) && dispatch_from == 'PARTY' && tripMandateOnReceipt == false) {
        } else {
            var trip_code = $('#tblmilkvehicleentry-trip_code').val();
            $('#tblmilkvehicleentry-dispatch_from').val(null).trigger('change');
            $('#tblmilkvehicleentry-dispatch_from_code').val(null).trigger('change');
            if (trip_code) {
                SourceData(trip_code);
            }
        }
        
    });
   
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
                url: '" . Url::to(['/tankermovement/tbl-milk-vehicle-entry/view-config']) . "',
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
$this->registerJs($script, View::POS_END, 'receipt-config-popup');
?>
