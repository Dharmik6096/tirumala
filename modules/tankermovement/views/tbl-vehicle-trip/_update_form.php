<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use softark\duallistbox\DualListbox;

$tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'tanker_movement_with_trip_sub_status', 'PORTAL');
$readonly = TRUE;
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
    <?= Html::activeHiddenInput($model, 'trip_code') ?>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletrip-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
    </div>
    <div class="col-sm-2">
        <?php
        if($tankerMovementWithTripSubStatus) {
            echo Yii::$app->dropdown->vehicleQaInspectionList($model, $form, 'tblvehicletrip-union_code,tblvehicletrip-transporter_code', 'vehicle_code', TRUE, FALSE, '', FALSE, TRUE);
        } else {
            echo Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblvehicletrip-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE);
        } ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'driver_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput() ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_auto_trip', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox()->label('Is Partial Trip?'); ?>
    </div>
    <div class="col-sm-6">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehicletrip-union_code', 'plant_code', Yii::t('app', 'Plant'), true); ?>
    </div>
    <div class="col-sm-6">
        <label class="control-label">Dispatch already taken</label>
        <div class="dispatch-box">
            <?php
            if(!empty($model->takenTripDetailCode)) {
                foreach($model->takenTripDetailCode as $key => $value) { 
                    $name = '';
                    $response = Yii::$app->general->getColumnName($value->source_org_type);
                    if (!empty($response['rel'])) {
                        $sourceData = $value->{$response['rel'] . 'Source'};
                        $name = $sourceData->{$response['name']} . ' - '. $sourceData->{$response['ref_code']};
                    } ?>
                    <p><?php echo $name . ' - ' . strtoupper($value->source_org_type); ?></p>
                <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 megaSizeDualList">
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
                    'selected' => $model->bmc_code, 
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
$script = "
var selectedBmcCodes = JSON.parse('$bmcArray');

// Handle change event for plant code
$('#tblvehicletrip-plant_code').on('change', function() {
    var plant_code = $(this).val();
    var union_code = $('#tblvehicletrip-union_code').val();
    var action_type = $('#type').val();

    $.ajax({
        type: 'post',
        url: '" . Url::to(['/organisation/tbl-dcs-bmc/get-plant-bmc-with-party']) . "',    
        data: 'union_code='+union_code+'&plant_code='+plant_code+'&action_type='+action_type,
        success: function(data) {
            console.log(data);
            var obj1 = $.parseJSON(data);
            if (obj1.status == 'success') {
                var selectedBmcSet = new Set(selectedBmcCodes);
                var options = '';

                // Clear existing options
                $('#tblvehicletrip-bmc_code').empty();

                // Add selected BMC codes
                $.each(selectedBmcCodes, function(index, code) {
                    if (obj1.data.hasOwnProperty(code)) {
                        options += '<option value=\"' + code + '\" data-sortindex=\"' + index + '\" selected>' + obj1.data[code] + '</option>';
                    }
                });

                // Add new options
                $.each(obj1.data, function(index, value) {
                    if (!selectedBmcSet.has(index)) {
                        options += '<option value=\"' + index + '\" data-sortindex=\"' + index + '\">' + value + '</option>';
                    }
                });

                // Append options to the dual listbox
                $('#tblvehicletrip-bmc_code').append(options);
                $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);
            }
        }
    });           
});

// Handle form submission
$('#vehicle-trip-form').submit(function(e) {
    var bmcarray = '';                                      
    $('#tblvehicletrip-bmc_code option:selected').each(function() {
        var sortIndex = $(this).attr('data-sortindex'); // Ensure this attribute is set
        var value = $(this).val();
        if (sortIndex) {
            bmcarray += sortIndex + '~~~' + value + ':::';
        } else {
            bmcarray += value + ':::';
        }
    });
    $('#selected_bmc_seq').val(bmcarray);      
});

// Vehicle code change handling
$('#tblvehicletrip-vehicle_code').on('change', function() {
    var vehicle_code = $(this).val();
    if (vehicle_code != '') {
        $.ajax({
            type: 'post',
            url: '" . Url::to(['get-vehicle-detail']) . "',    
            data: { vehicle_code: vehicle_code },
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'success') {
                    var response = obj1.data;
                    if (response) {
                        $('#tblvehicletrip-driver_name').val(response.driver_name);
                        $('#tblvehicletrip-mobile_no').val(response.driver_contact_no);
                    }
                }
            }
        });
    }
});

// Handle change event for the dual listbox
$('#tblvehicletrip-bmc_code').change(function() {
    var selectedOptions = $('#tblvehicletrip-bmc_code option:selected');
    var nonSelectedOptions = $('#tblvehicletrip-bmc_code option:not(:selected)').map(function() { return this.value; }).get();                        
    var bmcArraySel = {};

    selectedOptions.each(function() {
        var val = $(this).val();
        var txt = $(this).text();
        bmcArraySel[val] = txt;
    });

    $.each(bmcArraySel, function(index, value) {
        if (jQuery.inArray(index, nonSelectedOptions) == -1) {
            $('#tblvehicletrip-bmc_code').append($('<option></option>').attr('value', index).text(value).attr('data-sortindex', index)); 
        }
        nonSelectedOptions.push(index);
    });  

    $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);      
});
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-bmc-list');
?>