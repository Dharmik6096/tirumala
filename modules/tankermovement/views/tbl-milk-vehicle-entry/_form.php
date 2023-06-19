
<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

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
            <div class="col-sm-2"> 
                <?= Yii::$app->dropdown->dropdownStatic('receipt_at', $model, $form, 'form-group', $model->getAttributeLabel('receipt_at'), false, 'receipt_at', false); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkvehicleentry-union_code', 'plant_code', TRUE); ?>
            </div>
            <div class="col-sm-2 receipt_hide">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkvehicleentry-plant_code', 'mcc_plant_code', TRUE); ?>
            </div>
            <div class="col-sm-2 receipt_hide">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkvehicleentry-mcc_plant_code', 'bmc_code', TRUE); ?>
            </div>
            <div class="col-sm-2 receipt_hide">
                <?= Yii::$app->dropdown->customer_type($model, $form, 'tblmilkvehicleentry-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
            </div>
            <div class="col-sm-2 receipt_hide">
                <?= Yii::$app->dropdown->customer_code($model, $form, 'tblmilkvehicleentry-bmc_code,tblmilkvehicleentry-customer_type', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE); ?>
            </div>            
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'vehicle_entry_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2"> 
                <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblmilkvehicleentry-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Html::hiddenInput('trip_code', $model->trip_code, ['id' => 'trip_code']); ?>
                <?= Html::hiddenInput('trip_type', 'receipt', ['id' => 'trip_type']); ?>
                <?= Yii::$app->dropdown->vehicleOpenTrip($model, $form, 'trip_type,tblmilkvehicleentry-vehicle_code,tblmilkvehicleentry-vehicle_entry_date,trip_code', 'trip_code', $model->getAttributeLabel('trip_code'), false, false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'arrival_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'qty')->textInput() ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'gross_weight')->textInput() ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'tare_weight')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'tare_weight_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
        </div>
        <div class="col-lg-12">
            <h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Summary') ?></h5>
            <div id="dispatch-detail">
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
    $('.receipt_hide').hide();
    $(document).on('change','#tblmilkvehicleentry-receipt_at', function() {
    var receipt=$('#tblmilkvehicleentry-receipt_at').val();
        if(receipt !='' && receipt=='VENDOR'){
         $('.receipt_hide').show();
        } else if(receipt !='' && receipt=='PLANT'){
            $('.receipt_hide').hide();
            $('#tblmilkvehicleentry-mcc_plant_code').val('');
            $('#tblmilkvehicleentry-bmc_code').val('');
            $('#tblmilkvehicleentry-customer_type').val('');
            $('#tblmilkvehicleentry-customer_code').val('');
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
          var plant_code = $('#tblmilkvehicleentry-plant_code').val();
          var union_code = $('#tblmilkvehicleentry-union_code').val();  
          $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'plant_code' : plant_code,'union_code':union_code},             
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