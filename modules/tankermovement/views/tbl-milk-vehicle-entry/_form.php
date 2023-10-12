
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
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'dispatch_from'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilkvehicleentry-dispatch_from,tblmilkvehicleentry-union_code', 'dispatch_from_code', $model->getAttributeLabel('dispatch_from_code'), FALSE, $readonly); ?>
            </div>
            <div class="col-sm-1">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, $readonly, 'receipt_at'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblmilkvehicleentry-receipt_at,tblmilkvehicleentry-union_code', 'receipt_at_code', $model->getAttributeLabel('receipt_at_code'), FALSE, $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'receipt_datetime', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, $readonly, 'receipt_shift_code'); ?>
            </div>
            <div class="col-sm-2 disabled vehicle_code_hide"> 
                <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblmilkvehicleentry-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE); ?>
            </div>
            <div class="col-sm-2 tanker_no_hide"> 
                <?= $form->field($model, 'tanker_no')->textInput() ?>
            </div>
            <div class="col-sm-2 filldata trip-code-hide">
                <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                <?= Html::hiddenInput('trip_type', 'receipt', ['id' => 'trip_type']); ?>
                <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'trip_type,tblmilkvehicleentry-vehicle_code,tblmilkvehicleentry-receipt_datetime,trip_code', 'trip_code', $model->getAttributeLabel('trip_code'), false, false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'arrival_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'gross_weight')->textInput() ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'tare_weight')->textInput() ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'qty')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'tare_weight_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
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
                            <th>In Time</th>                   
                            <th>Out Time</th>                   
                            <th>Gross Weight</th>                   
                            <th>Tare Weight</th>     
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
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
                    <?= $form->field($txn_model, 'source_org_code')->hiddenInput()->label(FALSE) ?>
                    <?= $form->field($txn_model, 'source')->textInput() ?>
                </div>
                <div class="col-sm-2 type_hide disabled">
                    <?= $form->field($txn_model, 'destination_type')->textInput() ?>
                </div>
                <div class="col-sm-2 type_hide disabled">
                    <?= $form->field($txn_model, 'destination_code')->hiddenInput()->label(FALSE) ?>
                    <?= $form->field($txn_model, 'destination')->textInput() ?>
                </div>
                <div class="col-sm-1"> 
                    <?= Yii::$app->dropdown->dropdown('milk_type_code', $txn_model, $form, '', true, FALSE, 'milk_type_code'); ?>
                </div>
                <div class="col-sm-1"> 
                    <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $txn_model, $form, '', true, FALSE, 'milk_quality_type_code'); ?>
                </div>
                <div class="col-sm-1"> 
                    <?= Yii::$app->dropdown->dropdownStatic('chamber_no', $txn_model, $form, 'form-group', $txn_model->getAttributeLabel('chamber_no'), false, 'chamber_no', false); ?>
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
                                                                    $("#tblmilkvehicleentry-milk_vehicle_entry_code").val(data.milk_vehicle_entry_code);
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $(".master_fields").addClass("disabled");
                                                                    $(".entry_type").addClass("disabled");
                                                                    $("#entry_type").val($("#tblmilkvehicleentrytransaction-entry_type" ).val());
                                                                    $("#milk-vehicle-form .master_fields select").attr("disabled", true);
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
    <?php ActiveForm::end(); ?>
    <div class="col-lg-12">
        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <?=
            $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
            ?>
        </div>
    </div>
</div>
<?php
$script = "
    
    $('#tblmilkvehicleentry-vehicle_code, #tblmilkvehicleentry-receipt_datetime').on('change', function() {
        var vehicleCode = $('#tblmilkvehicleentry-vehicle_code').val();
        var ReceiptDatetime = $('#tblmilkvehicleentry-receipt_datetime').val();
        if (vehicleCode !== '' && ReceiptDatetime !== '' && vehicleCode != null && ReceiptDatetime != null) {
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
    
    $(document).on('change', '#tblmilkvehicleentry-receipt_at, #tblmilkvehicleentry-dispatch_from', function() {
        var receipt_at = $('#tblmilkvehicleentry-receipt_at').val();
        var dispatch_from = $('#tblmilkvehicleentry-dispatch_from').val();
        var entryTypeField = $('#tblmilkvehicleentrytransaction-entry_type');
        var EntryType = document.querySelector('.col-sm-1.entry_type');
        
        if (receipt_at !== '' && dispatch_from !== '') {
            if ((dispatch_from == 'BMC' && receipt_at == 'PLANT') || (dispatch_from == 'BMC' && receipt_at == 'BMC') || (dispatch_from == 'BMC' && receipt_at == 'PARTY')) {
                $('.tanker_no_hide').css('display', 'none');
                $('.vehicle_code_hide').css('display', 'block');
                $('#dispatch-detail').css('display', 'block');                
                entryTypeField.val('').prop('disabled', false).trigger('change');
            } else if(dispatch_from == 'PARTY' && receipt_at == 'PARTY') {
               $('.tanker_no_hide').css('display', 'block');
               $('.vehicle_code_hide').css('display', 'none');
               $('#dispatch-detail').css('display', 'none');
               entryTypeField.val('CONSOLIDATED').prop('readonly', true).trigger('change');
               EntryType.classList.add('no_pointer');
            } else {
                $('.tanker_no_hide').css('display', 'none');
                $('.vehicle_code_hide').css('display', 'block');
                $('#dispatch-detail').css('display', 'none');
                entryTypeField.val('CONSOLIDATED').prop('readonly', true).trigger('change');
                EntryType.classList.add('no_pointer');
            }
        } else {
            $('.tanker_no_hide').css('display', 'none');
            $('.vehicle_code_hide').css('display', 'block');
            $('#dispatch-detail').css('display', 'none');
            entryTypeField.val('').prop('disabled', false).trigger('change');
            EntryType.classList.remove('no_pointer');
        }
    });
    
    $('.type_hide').hide();
    $(document).on('change','#tblmilkvehicleentrytransaction-entry_type', function() {
    var entry_type=$('#tblmilkvehicleentrytransaction-entry_type').val();
        if(entry_type !='' && entry_type=='INDIVIDUAL'){
         $('.type_hide').show();
        } else if(entry_type !='' && entry_type=='CONSOLIDATED'){
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
        if(challan_no !='' && trip_code!=''){
            setSourseDest(challan_no,trip_code);
        } 
    });
    
    
     $(document).on('change','.filldata', function() {
        var trip_code = $('#tblmilkvehicleentry-trip_code').val();
       if(trip_code != ''){
            $('#loadercontent').show();
            $('#pageloader').show();
            $('#dispatch-detail').html('');
            $('#transactions-from').html('');
            BindData(trip_code);
         //   GetVehicle(trip_code);
        }      
    });
    

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
                $('#tblmilkvehicleentrytransaction-source_org_type').val('BMC');
                $('#tblmilkvehicleentrytransaction-source_org_code').val(data.data.bmc_code);
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
            if(milk_vehicle_entry_transaction_code != ''){         
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
