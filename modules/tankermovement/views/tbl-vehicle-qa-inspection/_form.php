<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
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

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicleqainspection-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_code')); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblvehicleqainspection-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Html::hiddenInput('trip_process', 'qa_inspection', ['id' => 'trip_process']); ?>
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

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = "
   $(document).ready(function() {
    function setDefaultTripCode() {
        var tripDropdown = $('#tblvehicleqainspection-trip_code');
        var options = tripDropdown.find('option');
        if (options.length == 2) {
            var singleOption = options.eq(1).val();
            $('#tblvehicleqainspection-trip_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                $('#tblvehicleqainspection-trip_code').val(singleOption);
                $('#tblvehicleqainspection-trip_code').trigger('change');
                $('#tblvehicleqainspection-trip_code').trigger('select2:select');
            });
        }
    }
    $('#tblvehicleqainspection-vehicle_code').on('change', function() {
        setDefaultTripCode();
    });
  
});

";
$this->registerJs($script, View::POS_END, 'cleaning');
