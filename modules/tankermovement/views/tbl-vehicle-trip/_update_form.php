<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\sortable\Sortable;

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
    'action' => Url::to(array_merge(['update'], Yii::$app->request->get())),
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
        <?= Yii::$app->dropdown->dropdown('vehicle_transpoter', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), $readonly); ?>
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
    <div class="col-sm-2">
        <?= $form->field($model, 'no_of_compartment')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'vehicle_capacity')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2 mt10 mt-4">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_auto_trip', 'no_pointer'); ?>
    </div>
    <div class="col-sm-8">
        <?= $form->field($model, 'remark')->textInput(['readonly' => $readonly]) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php echo Html::hiddenInput('rls', 'FALSE', ['id' => 'tblvehicletrip-rls']); ?>
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehicletrip-union_code,tblvehicletrip-rls', 'plant_code', Yii::t('app', 'Plant'), true); ?>
    </div>
    <div class="col-sm-6">
        <label class="control-label">Dispatch already taken</label>
        <div class="dispatch-box">
            <?php
            if(!empty($model->takenTripDetailCode)) {
                foreach($model->takenTripDetailCode as $key => $value) { 
                    if($value->is_virtual_location != 2){
                        $name = '';
                        $response = Yii::$app->general->getColumnName($value->source_org_type);
                        if (!empty($response['rel'])) {
                            $sourceData = $value->{$response['rel'] . 'Source'};
                            $name = $sourceData->{$response['name']} . ' - '. $sourceData->{$response['ref_code']};
                            if($value->is_virtual_location == 1){
                                $name = $sourceData->{$response['name']} . ' - conversion vendor '. $sourceData->{$response['ref_code']};
                            }
                        } ?>
                        <p><?php echo $name . ' - ' . strtoupper($value->source_org_type); ?></p>
                    <?php
                    }
                }
            }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-sm-6 mt-2">
        <label class="control-label"><?= Yii::t('app', 'PLANT/BMC') ?></label>
        <div class="well box-well">
            <div class="sticky_head sticky-column">
                <button type="button" class="btn btn-default btn-block mb-10 width_100" id="btn-move-right" title="Move Selected to Right"><i class="fa fa-arrow-right"></i></button>
                <input type="text" class="form-control mb-2" id="search-available-bmc" placeholder="Search Available BMC...">
            </div>
            <?= Sortable::widget([
                'type' => Sortable::TYPE_LIST,
                'items' => [],
                'options' => ['id' => 'available-bmc-list', 'class' => 'list-group', 'style' => 'min-height: 370px;'],
                'itemOptions' => ['class' => 'list-group-item'],
            ]); ?>
        </div>
    </div>
    <div class="col-sm-6 mt-2">
        <label class="control-label"><?= Yii::t('app', 'PLANT/BMC') ?> Seleted</label>
        <div class="well box-well">
            <div class="sticky_head sticky-column">
                <button type="button" class="btn btn-default btn-block mb-10 width_100" id="btn-move-left" title="Move Selected to Left"><i class="fa fa-arrow-left"></i></button>
                <input type="text" class="form-control mb-2" id="search-selected-bmc" placeholder="Search Selected BMC...">
            </div>
            <?= Sortable::widget([
                'type' => Sortable::TYPE_LIST,
                'items' => $model->bmc_code ? array_map(function($code) {
                    return ['content' => Html::encode($code), 'options' => ['data-code' => $code]];
                }, $model->bmc_code) : [],
                'options' => ['id' => 'selected-bmc-list', 'class' => 'list-group', 'style' => 'min-height: 370px;'],
                'itemOptions' => ['class' => 'list-group-item'],
            ]); ?>

            <div id="bmc-code-container"></div>
            <?= Html::hiddenInput('selected_bmc_seq', '', ['id' => 'selected_bmc_seq']); ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group mt-2">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$jsFiles = [
    'core.min.js',
    'widget.min.js',
    'mouse.min.js',
    'sortable.min.js',
];

foreach ($jsFiles as $file) {
    $this->registerJsFile(Yii::getAlias('@web') . "/themes/emilk/assets/js/{$file}", [
        'depends' => [\yii\web\JqueryAsset::class],
        'position' => \yii\web\View::POS_END,
    ]);
}
$bmcArray = json_encode($model->bmc_code);
$script = "
var selectedBmcCodesInitial = $bmcArray;
$(document).ready(function() {
    $('.field-tblvehicletrip-transporter_code').addClass('disabled no_pointer');
    $('#tblvehicletrip-vehicle_code').trigger('change');
});
function updateSelectedBmcCodes() {
    var selectedCodes = $('#selected-bmc-list li').map(function() {
        return $(this).data('code');
    }).get();
    $('#bmc-code-container').empty();
    $.each(selectedCodes, function(index, code) {
        $('#bmc-code-container').append('<input type=\"hidden\" name=\"TblVehicleTrip[bmc_code][]\" value=\"' + code + '\">');
    });
    var selectedBmcSeq = selectedCodes.map((code, index) => index + '~~~' + code).join(':::');
    $('#selected_bmc_seq').val(selectedBmcSeq);
}

function populateBmcLists(data) {
    $('#available-bmc-list').empty();
    $('#selected-bmc-list').empty();
    var selectedBmcSet = new Set(selectedBmcCodesInitial);    
    $.each(data, function(code, name) {
        var listItem = '<li class=\"list-group-item\" data-code=\"' + code + '\">' + name + '</li>';
        if (selectedBmcSet.has(code)) {
            // $('#selected-bmc-list').append(listItem);
        } else {
            $('#available-bmc-list').append(listItem);
        }
    });
    $.each(data, function(code, name) {
        var listItem = '<li class=\"list-group-item\" data-code=\"' + code + '\">' + name + '</li>';
        if (selectedBmcSet.has(code)) {
            $('#available-bmc-list').append(listItem);
        }
    });
    $.each(selectedBmcCodesInitial, function(_, code) {
        if (data.hasOwnProperty(code)) {
            var name = data[code];
            var listItem = '<li class=\"list-group-item\" data-code=\"' + code + '\">' + name + '</li>';
            $('#selected-bmc-list').append(listItem);
        }
    });
    updateHiddenInputs();
}

function updateHiddenInputs() {
    var currentSelectedCodes = $('#selected-bmc-list li').map(function() {
        return $(this).data('code');
    }).get();
    $('#tblvehicletrip-bmc_code').val(currentSelectedCodes.join(','));
    $('#selected_bmc_seq').val(currentSelectedCodes.join(':::'));
}

$('#tblvehicletrip-plant_code').on('change', function() {
    var plant_code = $(this).val();
    var union_code = $('#tblvehicletrip-union_code').val();
    var action_type = $('#type').val();
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/organisation/tbl-dcs-bmc/get-plant-bmc-with-party']) . "',
        data: 'union_code='+union_code+'&plant_code='+plant_code+'&action_type='+action_type,
        success: function(data) {
            var obj1 = $.parseJSON(data);
            if (obj1.status == 'success') {
                populateBmcLists(obj1.data);
            } else {
                $('#available-bmc-list, #selected-bmc-list').empty();
                $('#tblvehicletrip-bmc_code, #selected_bmc_seq').val('');
            }
        }
    });
});

$('#tblvehicletrip-vehicle_code').on('change', function() {
    var vehicle_code = $(this).val();
    if (setData(vehicle_code)) {
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
    } else {
        $('#tblvehicletrip-driver_name, #tblvehicletrip-mobile_no').val('');
    }
});

function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    }else {
        return false;
    }
}

$(document).ready(function() {
    if ($('#tblvehicletrip-plant_code').val()) {
        $('#tblvehicletrip-plant_code').trigger('change');
    } else {
        var initialData = {};
        $.each(selectedBmcCodesInitial, function(index, code) {
            initialData[code] = code; // You might need to fetch actual names if available on load
        });
        populateBmcLists(initialData);
    }

    $('#vehicle-trip-form').on('beforeSubmit', function () {
        updateSelectedBmcCodes();
        return true;
    });

    $('#available-bmc-list, #selected-bmc-list').sortable({
        connectWith: '#available-bmc-list, #selected-bmc-list',
        helper: 'clone',
        update: function (event, ui) {
            var item = ui.item;
            var code = item.data('code');
            if (item.closest('#selected-bmc-list').length) {
                var selectedItems = $('#selected-bmc-list li').filter(function () {
                    return $(this).data('code') === code;
                });
                if (item.parent().is('#available-bmc-list')) {
                    item.remove();
                }
            }
            if (item.closest('#available-bmc-list').length) {
                var alreadyExists = $('#available-bmc-list li').filter(function () {
                    return $(this).data('code') === code;
                });
                if (alreadyExists.length === 0) {
                    var listItem = $('<li class=\"list-group-item\" data-code=\"' + code + '\">' + item.text() + '</li>');
                    $('#available-bmc-list').append(listItem);
                } else {
                    item.remove(); // prevent duplicate in available
                }
            }
            updateSelectedBmcCodes();
        },
        receive: function (event, ui) {
            if ($(this).attr('id') === 'selected-bmc-list') {
                var code = ui.item.data('code');
                var existsInAvailable = $('#available-bmc-list li').filter(function () {
                    return $(this).data('code') === code;
                });
                if (existsInAvailable.length === 0) {
                    var original = ui.item.clone();
                    original.removeClass(\"ui-sortable-helper\"); // just in case
                    $('#available-bmc-list').append(original);
                }
            }
        }
    });
});

$('#search-available-bmc').on('keyup', function () {
    var search = $(this).val().toLowerCase();
    $('#available-bmc-list li').each(function () {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.includes(search));
    });
});

$('#search-selected-bmc').on('keyup', function () {
    var search = $(this).val().toLowerCase();
    $('#selected-bmc-list li').each(function () {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.includes(search));
    });
});
$('body').on('click', '.list-group-item', function() {
    $(this).toggleClass('active');
});

$('#btn-move-right').on('click', function() {
    $('#available-bmc-list .active').each(function() {
        var li = $(this);
        var code = li.data('code');
        li.removeClass('active');
        $('#available-bmc-list').append(li);            
        var exists = $('#selected-bmc-list li').filter(function() { return $(this).data('code') == code; }).length > 0;            
        if (!exists) {
            var clone = li.clone();
            clone.removeClass('active');
            $('#selected-bmc-list').append(clone);
        }
    });
    updateSelectedBmcCodes();
});

$('#btn-move-left').on('click', function() {
    $('#selected-bmc-list .active').each(function() {
        var li = $(this);
        li.remove();
    });
    updateSelectedBmcCodes();
});

(function() {
    document.addEventListener('touchstart', touchHandler, true);
    document.addEventListener('touchmove', touchHandler, true);
    document.addEventListener('touchend', touchHandler, true);
    document.addEventListener('touchcancel', touchHandler, true);

    function touchHandler(event) {
        var touches = event.changedTouches,
            first = touches[0],
            type = '';

        switch(event.type) {
            case 'touchstart': type = 'mousedown'; break;
            case 'touchmove':  type = 'mousemove'; break;        
            case 'touchend':   type = 'mouseup';   break;
            default: return;
        }

        var simulatedEvent = document.createEvent('MouseEvent');
        simulatedEvent.initMouseEvent(type, true, true, window, 1, 
            first.screenX, first.screenY, 
            first.clientX, first.clientY, false, 
            false, false, false, 0, null);

        first.target.dispatchEvent(simulatedEvent);
    }
})();
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-sortable-bmc-list');
?>