<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row theme_border_left theme_border_right theme_border_bottom">
    <?= Html::hiddenInput('trip_process', 'qa_inspection', ['id' => 'trip_process']); ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->vehicleList($model, $form, 'tblvehicleqainspection-union_code,trip_process', 'vehicle_code', TRUE, FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicleqainspection-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->vehicleOpenTripDetail($model, $form, 'tblvehicleqainspection-union_code,trip_process,tblvehicleqainspection-vehicle_code', 'trip_code', $model->getAttributeLabel('trip_code')); ?>
    </div>
    <div class="col-sm-4"> 
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="clearfix margin-bottom-10"></div>
    <?php
    $index = 1;
    $cnt = 1;
    foreach ($config_list as $c) {
        ?>
        <?= Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]); ?>
        <div class="col-sm-2 margin_left15">
            <?= $c->prepareControl($form, $config, $index); ?>
        </div>
        <?php if ($cnt == 6) { ?>
            <div class="clearfix"></div>
            <?php
            $cnt = 0;
        }
        ?>
        <?php
        $cnt++;
        $index++;
    }
    ?>
    <div class="clearfix"></div>

</div>
<div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = "
   $(document).ready(function() {
    $('.field-tblvehicleqainspection-transporter_code').addClass('no_pointer_disabled');
    function autoSelectFields() {
            $('#tblvehicleqainspection-trip_code').trigger('change');
            $('#tblvehicleqainspection-trip_code').trigger('select2:select');
            $('#tblvehicleqainspection-union_code').trigger('change');
            $('#tblvehicleqainspection-union_code').trigger('select2:select');
            $('#tblvehicleqainspection-transporter_code').trigger('change');
            $('#tblvehicleqainspection-transporter_code').trigger('select2:select');
            $('#tblvehicleqainspection-vehicle_code').trigger('change');
            $('#tblvehicleqainspection-vehicle_code').trigger('select2:select');
    }
        
     autoSelectFields();
    
    function setDefaultTripCode() {
        var tripDropdown = $('#tblvehicleqainspection-trip_code');
        var options = tripDropdown.find('option');
        if (options.length == 2) {
            var singleOption = options.eq(1).val();
            tripDropdown.val(singleOption).trigger('change');
        }
    }
    $('#tblvehicleqainspection-vehicle_code').on('change', function() {
        var vehicle_code = $(this).val();
        if(setData(vehicle_code)){
            setTranspoter(vehicle_code);
        }else{
            $('#tblvehicleqainspection-transporter_code').val('').trigger('change').trigger('select2:select');
        }

        $('#tblvehicleqainspection-trip_code').on('depdrop.afterChange', function(event, id, value) {
            setDefaultTripCode();
         });
    });
  
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }
    function setTranspoter(vehicle_code){
        if(vehicle_code != ''){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/transporter/tbl-vehicle-master/get-vehicle-transpoter']) . "', 
                data:{'vehicle_code':vehicle_code},
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if(obj1.status == 'success'){
                        if(setData(obj1.data)){
                            $('#tblvehicleqainspection-transporter_code').val(obj1.data.transporter_code).trigger('change').trigger('select2:select');
                        }
                    }
                }
            });
        }
    }
    
    $('.apply-shortcut[type=\"reset\"]').on('click', function () {
        $('#tblvehicleqainspection-vehicle_code').val('').trigger('change');
        $('#tblvehicleqainspection-transporter_code').val('').trigger('change');
        $('#tblvehicleqainspection-trip_code').val('').trigger('change');
    });
});

";
$this->registerJs($script, View::POS_END, 'cleaning');
