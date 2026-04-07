<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use softark\duallistbox\DualListbox;

$tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'tanker_movement_with_trip_sub_status', 'PORTAL');
?>
<?php
$form = ActiveForm::begin([
            'id' => 'vehicle-trip-form',
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <?= Html::activeHiddenInput($model, 'type', ['id' => 'type']) ?>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?php
        if ($tankerMovementWithTripSubStatus) {
            echo Yii::$app->dropdown->vehicleQaInspectionList($model, $form, 'tblvehicletrip-union_code', 'vehicle_code', TRUE, FALSE, '', FALSE, TRUE);
        } else {
            echo Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'));
        }
        ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletrip-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'driver_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'no_of_compartment')->textInput(['readonly' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'vehicle_capacity')->textInput(['readonly' => true]) ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_auto_trip', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox()->label('Is Partial Trip?'); ?>
    </div>
    <div class="col-sm-8">
        <?= $form->field($model, 'remark')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php echo Html::hiddenInput('rls', 'FALSE', ['id' => 'tblvehicletrip-rls']); ?>
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehicletrip-union_code,tblvehicletrip-rls', 'plant_code', Yii::t('app', 'Plant'), true, '', false, false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 megaSizeDualList customDaulBoxCss">
        <?php
        echo $form->field($model, 'bmc_code', ['options' => ['class' => 'form-group col-sm-12'], 'labelOptions' => ['label' => Yii::t('app', 'PLANT/BMC*')]])
                ->widget(DualListbox::className(), [
                    'items' => [],
                    'options' => [
                        'multiple' => true,
                        'size' => 20
                    ],
                    'clientOptions' => [
                        'moveOnSelect' => FALSE,
                        'selectedListLabel' => FALSE,
                        'nonSelectedListLabel' => FALSE,
                        'filterPlaceHolder' => '',
                        'sortByInputOrder' => TRUE,
                    ],
        ]);
        echo Html::hiddenInput('selected_bmc_seq', '', ['id' => 'selected_bmc_seq']);
        ?>
    </div>
    <div class="clearfix"></div>
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
$bmcArray = json_encode($model->bmc_code);
$isNotActualPlant = json_encode($model->is_not_actual_plant);
$script = "
var selectedBmcCodesInitial = $bmcArray;
var isNotActualPlant = $isNotActualPlant;
var isLoadPage = true;
$(document).ready(function() {
    $('.field-tblvehicletrip-transporter_code').addClass('disabled no_pointer');
});

$('#tblvehicletrip-plant_code').on('change',function(){
    var plant_code = $('#tblvehicletrip-plant_code').val(); 
    var union_code = $('#tblvehicletrip-union_code').val();
    var action_type = $('#type').val();
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/organisation/tbl-dcs-bmc/get-plant-bmc']) . "',    
        data: 'union_code='+union_code+'&plant_code='+plant_code+'&action_type='+action_type,
        success: function(data) {
            var obj1 = $.parseJSON(data);
            if (obj1.status == 'success') {                          
                var mccarray =  $('#tblvehicletrip-bmc_code option:selected');
                var selarray =  mccarray.map(function () {
                    return this.value;
                }).get();
                $('#tblvehicletrip-bmc_code option').remove();                              
                var options='';  

                $.each(plant_code, function(index, plant_code) {
                    if ($.inArray(plant_code, isNotActualPlant) === -1) {
                        var uniquePlantValue = plant_code + '#plant';
                        var plantText = $('#tblvehicletrip-plant_code option[value=\"' + plant_code + '\"]').text();
                        options += '<option value=\"' + uniquePlantValue + '\">' + plantText + ' - PLANT</option>';
                    }
                });
                $.each(obj1.data, function(index, value) {
                    // if(jQuery.inArray(index,selarray) == -1){   
                        options += '<option value=\"'+index+'\">'+value+'</option>';  
                    // }
                });

                var container = $('#tblvehicletrip-bmc_code').bootstrapDualListbox('getContainer');
                container.find('input.filter').val('').trigger('input');
                container.find('select').trigger('change');

                $('#tblvehicletrip-bmc_code').html(options);
                $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);

                var moveSelected = function (items) {
                    var sourceSelect = container.find('.box1 select');
                    $.each(items, function (i, valueToSelect) {
                        var optionToMove = sourceSelect.find('option:not(:selected)[value=\"' + valueToSelect + '\"]').first();
                        if (optionToMove.length > 0) {
                            optionToMove.prop('selected', true);
                            container.find('.box1 .move').trigger('click');
                        }
                    });
                };

                setTimeout(function () {
                    if (selectedBmcCodesInitial.length > 0 && isLoadPage) {
                        moveSelected(selectedBmcCodesInitial);
                        isLoadPage = false;
                    } else if (selarray.length > 0) {
                        moveSelected(selarray);
                    }
                }, 500);
            }
        }
    });           
});
// $('#vehicle-trip-form').submit(function(e) {
//     var bmcarray = '';                                      
//     var options = $('#tblvehicletrip-bmc_code option:selected');
//     console.log(options);
//     options.each(function(index){
//         bmcarray += index+'~~~'+$(this).attr('value')+':::';
//     });
//     $('#selected_bmc_seq').val(bmcarray);      
// });
$('#vehicle-trip-form').submit(function(e) {
    var bmcarray = '';
    var dualListBoxContainer = $('.bootstrap-duallistbox-container');
    var selectedOptions = dualListBoxContainer.find('.box2 select option');

    var sortedOptions = selectedOptions.sort(function(a, b) {
        var indexA = $(a).attr('data-sortindex');
        var indexB = $(b).attr('data-sortindex');

        var numA = indexA ? parseInt(indexA, 10) : Number.MAX_SAFE_INTEGER;
        var numB = indexB ? parseInt(indexB, 10) : Number.MAX_SAFE_INTEGER;

        if (isNaN(numA) && isNaN(numB)) {
            return 0;
        }
        if (isNaN(numA)) {
            return 1;
        }
        if (isNaN(numB)) {
            return -1;
        }

        return numA - numB;
    });
    sortedOptions.each(function(index) {
        bmcarray += index + '~~~' + $(this).attr('value') + ':::';
    });
    $('#selected_bmc_seq').val(bmcarray);
});
$('#tblvehicletrip-vehicle_code').on('change', function(){
    var vehicle_code = $(this).val();
    var vehicle_name = $(this).find('option:selected').text();
    if(setData(vehicle_code)){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['get-vehicle-detail']) . "',    
            data: 'vehicle_code='+vehicle_code,
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                    var response = obj1.data;
                    if(response != '' && response != null){
                        $('#tblvehicletrip-driver_name').val(response.driver_name);
                        $('#tblvehicletrip-mobile_no').val(response.driver_contact_no);
                        $('#tblvehicletrip-no_of_compartment').val(response.compartment_no);
                        $('#tblvehicletrip-vehicle_capacity').val(response.capacity);
                        $('#tblvehicletrip-transporter_code').val(response.transporter_code).trigger('change').trigger('select2:select');
                    } else {
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Compartment not available for selected vehicle: '+vehicle_name+'</span></div></div>');
                        $('#tblvehicletrip-vehicle_code').val(null).trigger('change');
                    }
                }
            }
        });
    } else {
        $('#tblvehicletrip-driver_name, #tblvehicletrip-mobile_no, #tblvehicletrip-no_of_compartment, #tblvehicletrip-vehicle_capacity, #tblvehicletrip-transporter_code').val(null).trigger('change');
    }
});

function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    }else {
        return false;
    }
}
";
$script .= "
$('#tblvehicletrip-bmc_code').change(function () {
    var mccarray =  $('#tblvehicletrip-bmc_code option:selected');
    var nonselarray =  $('#tblvehicletrip-bmc_code option:not(:selected)').map(function () {return this.value;}).get();                        
    var bmc_array_sel = {};
    mccarray.each(function(){
        var val = $(this).attr('value');
        var txt = $(this).text();
        bmc_array_sel[val]=txt;
    });
    $.each(bmc_array_sel, function(index, value) {
        if(jQuery.inArray(index,nonselarray) == -1){
            $('#tblvehicletrip-bmc_code').append($('<option></option>').attr('value', index).text(value)); 
        }
        nonselarray.push(index);
    });
    $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);
});
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-bmc-list');
?>