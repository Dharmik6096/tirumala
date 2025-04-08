<?php

use yii\helpers\Html;
use app\components\ActiveForm;
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
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', FALSE, FALSE, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code')); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletrip-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'driver_name')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2 mt10">
        <?= $form->field($model, 'is_auto_trip', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['disabled' => $readonly])->label('Is Partial Trip?'); ?>
    </div>
    <div class="col-sm-6">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehicletrip-union_code', 'plant_code', Yii::t('app', 'Plant'), true); ?>
    </div>
    <div class="col-sm-6">
        <label class="control-label">Dispatch already taken</label>
        <div class="dispatch-box">
            <?php
            if (!empty($model->takenTripDetailCode)) {
                foreach ($model->takenTripDetailCode as $key => $value) {
                    $name = '';
                    $response = Yii::$app->general->getColumnName($value->source_org_type);
                    if (!empty($response['rel'])) {
                        $sourceData = $value->{$response['rel'] . 'Source'};
                        $name = $sourceData->{$response['name']} . ' - ' . $sourceData->{$response['ref_code']};
                    }
                    ?>
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
    
$(document).ready(function() {
    $('.field-tblvehicletrip-transporter_code').addClass('disabled no_pointer');
});

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
                var newOptions = [];

                $('#tblvehicletrip-bmc_code').empty();

                $.each(selectedBmcCodes, function(index, code) {
                    if (obj1.data.hasOwnProperty(code)) {
                        newOptions.push({ code: code, name: obj1.data[code], sortIndex: index, selected: true });
                    }
                });

                $.each(obj1.data, function(code, name) {
                    if (!selectedBmcSet.has(code)) {
                        newOptions.push({ code: code, name: name, sortIndex: Object.keys(obj1.data).indexOf(code), selected: false });
                    }
                });

                newOptions.sort(function(a, b) {
                    return a.sortIndex - b.sortIndex;
                });

                $.each(newOptions, function(index, option) {
                    var selectedAttr = option.selected ? 'selected' : '';
                    $('#tblvehicletrip-bmc_code').append('<option value=\"' + option.code + '\" data-sortindex=\"' + option.sortIndex + '\" ' + selectedAttr + '>' + option.name + '</option>');
                });
                
                $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);
            }
        }
    });
});

// Handle form submission
$('#vehicle-trip-form').submit(function(e) {
    var bmcarray = '';
    $('#tblvehicletrip-bmc_code option:selected').each(function() {
        var sortIndex = $(this).data('sortindex');
        var value = $(this).val();
        if (typeof sortIndex !== 'undefined') {
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
    if(setData(vehicle_code)){
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
                        $('#tblvehicletrip-transporter_code').val(response.transporter_code).trigger('change').trigger('select2:select');
                    }
                }
            }
        });
    }
});

    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }
// Handle change event for the dual listbox (adjusting sort index on move)
$('#tblvehicletrip-bmc_code').change(function() {
    var selectedOptions = $('#tblvehicletrip-bmc_code option:selected');
    selectedOptions.each(function(index) {
        $(this).attr('data-sortindex', index);
    });
    $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);
});

$('#tblvehicletrip-bmc_code').bootstrapDualListbox({
    moveOnSelect: FALSE,
    selectedListLabel: FALSE,
    nonSelectedListLabel: FALSE,
    filterPlaceHolder: '',
    sortByInputOrder: TRUE,
    selected: selectedBmcCodes
});
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-bmc-list');
?>