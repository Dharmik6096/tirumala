<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
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
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehiclekminfo-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter', 'transporter_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblvehiclekminfo-transporter_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('vehicle_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('route_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route', $readonly, 'route_code'); ?>
    </div>
    <!--        <div class="col-sm-2 default_hide"> 
        <? Yii::$app->dropdown->depend_dropdown('routemapping', $model, $form, 'tblvehiclekminfo-union_code', 'form-group col-sm-4', $model->getAttributeLabel('route_code'), '', FALSE); ?>
            </div>-->
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, false, $readonly); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'morning_kms')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'evening_kms')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'extra_kms')->textInput(['class' => 'form-control extra_km']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'total_kms')->textInput(['readOnly' => true]) ?>
    </div>    
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'morning_arrival_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',]);
        ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?=
        $form->field($model, 'morning_grace_time')->textInput();
        ?>
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'evening_arrival_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',]);
        ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?=
        $form->field($model, 'evening_grace_time')->textInput();
        ?>
    </div>

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
  totalKm();
    $('#tblvehiclekminfo-morning_kms').change(function(){
        totalKm();
    });
    $('#tblvehiclekminfo-evening_kms').change(function(){
        totalKm();
    });
    $('#tblvehiclekminfo-extra_kms').change(function(){
        totalKm();
    });
    function totalKm(){
        var totalKm = 0;
        var mrng = parseFloat($('#tblvehiclekminfo-morning_kms').val());
        var evng = parseFloat($('#tblvehiclekminfo-evening_kms').val());
        var extra_km = parseFloat($('#tblvehiclekminfo-extra_kms').val());
        if(mrng == '' || isNaN(mrng)){
            mrng = 0;
        }
        if(evng == '' || isNaN(evng)){
            evng = 0;
        }
        if(isNaN(extra_km)){
            extra_km = 0;
        }
        totalKm = mrng + evng + extra_km;
        $('#tblvehiclekminfo-total_kms').val(totalKm.toFixed(2));
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>