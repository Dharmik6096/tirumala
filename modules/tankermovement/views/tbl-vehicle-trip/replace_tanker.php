<?php

use app\components\ActiveForm;
use kartik\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Breakdown Replacement');
$tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'tanker_movement_with_trip_sub_status', 'PORTAL');
?>
<div class="tbl-vehicle-trip-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['id' => 'form-replace-tanker']); ?>

            <div class="row">
                <?= Html::hiddenInput('trip_process', 'replace_tanker', ['id' => 'trip_process']); ?>
                <div class="col-sm-2" id="union">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
                </div>
                <div class="col-md-6">
                    <div class="background_shadow pd0">
                        <div class="theme-box-heading">Select Broken Tanker</div>
                        <div class="replacement-body">
                            <?= Yii::$app->dropdown->vehicleOpenTripDetail($model, $form, 'tblvehicletrip-union_code,trip_process', 'old_trip_code', $model->getAttributeLabel('trip_code')); ?>
                            <div id="trip-details-container">
                                <div class="empty-placeholder">
                                    <p class="text-muted text-center mb-0">Select a trip to see current details</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="background_shadow pd0">
                        <div class="theme-box-heading">Assign New Tanker</div>
                        <div class="replacement-body">
                            <?php
                            if ($tankerMovementWithTripSubStatus) {
                                echo Yii::$app->dropdown->vehicleQaInspectionList($model, $form, 'tblvehicletrip-union_code', 'vehicle_code', TRUE, FALSE, '', FALSE, TRUE);
                            } else {
                                echo Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'));
                            }
                            ?>
                            <div class="row driver-details">
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'driver_name')->textInput(['placeholder' => 'Driver Name']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'mobile_no')->textInput(['placeholder' => 'Mobile No']) ?>
                                </div>
                            </div>

                            <div id="vehicle-details-container">
                                <div class="empty-placeholder">
                                    <p class="text-muted text-center mb-0">New vehicle info will appear here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-sm-12 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model, 'btn btn-login'); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$getVehicleUrl = Url::to(['get-vehicle-detail-html']);
$getTripUrl = Url::to(['get-trip-detail-html']);
$oldTripParam = Yii::$app->request->get('old_trip_code');

$script = <<< JS
var hasOldTrip = "{$oldTripParam}";
if(hasOldTrip != ""){
    $('#tblvehicletrip-old_trip_code').parent('div').addClass('disabledDiv');
}
$('.driver-details').hide();
function showLoader(container) {
    container.html('<div class="text-center py-4"><div class="loader-simple"></div><p class="text-muted mb-0 mt-2">Fetching data...</p></div>');
}

function resetDetails(container, text) {
    container.html('<div class="empty-placeholder"><p class="text-muted text-center mb-0">' + text + '</p></div>');
}

$('#tblvehicletrip-vehicle_code').on('change', function() {
    var vehicleCode = $(this).val();
    var vehicleName = $(this).find('option:selected').text();
    var container = $('#vehicle-details-container');
    if(vehicleCode) {
        showLoader(container);
        $.post('{$getVehicleUrl}', {vehicle_code: vehicleCode, vehicle_name: vehicleName}, function(response) {
            var data = $.parseJSON(response);
            if(data.status == 'success'){
                container.html(data.html);
                $('.driver-details').show();
            } else {
                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' + data.msg + '</span></div></div>');
                $('#tblvehicletrip-vehicle_code').val(null).trigger('change');
                resetDetails(container, 'New vehicle info will appear here');
            }
        });
    } else {
        $('#tblvehicletrip-driver_name').val('');
        $('#tblvehicletrip-mobile_no').val('');
        $('.driver-details').hide();
        resetDetails(container, 'New vehicle info will appear here');
    }
});

$('#tblvehicletrip-old_trip_code').on('change', function() {
    var tripCode = $(this).val();
    var container = $('#trip-details-container');    
    
    if(tripCode) {
        showLoader(container);
        $.post('{$getTripUrl}', {trip_code: tripCode}, function(response) {
            container.html(response);
        });
        $('#tblvehicletrip-vehicle_code').closest('.form-group').removeClass('disabledDiv');
        $('.btn-login').prop('disabled', false).removeClass('disabledDiv');
    } else {
        resetDetails(container, 'Select a trip to see current details');
        $('#tblvehicletrip-vehicle_code').closest('.form-group').addClass('disabledDiv');
        $('.btn-login').prop('disabled', true).addClass('disabledDiv');
        if ($('#tblvehicletrip-vehicle_code').val()) {
            $('#tblvehicletrip-vehicle_code').val(null).trigger('change');
        }
    }
});

if (!$('#tblvehicletrip-old_trip_code').val()) {
    $('#tblvehicletrip-vehicle_code').closest('.form-group').addClass('disabledDiv');
    $('.btn-login').prop('disabled', true).addClass('disabledDiv');
} else {
    setTimeout(function() {
        $('#tblvehicletrip-old_trip_code').trigger('change');
    }, 500);
}
JS;
$this->registerJs($script);
?>