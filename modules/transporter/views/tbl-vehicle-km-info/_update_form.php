<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Vehicle KM Information');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<h5 class="panel-subtitle"></h5>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('transporter_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0','Transporter',true,'transporter_code');  ?>
    </div>
    <div class="col-sm-3">
    <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code','Vehicle','true'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('route_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0','Route',true,'route_code');  ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false,false,true); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'morning_kms')->textInput(['class'=>'form-control morning_km']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'evening_kms')->textInput(['class'=>'form-control evening_km']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'extra_kms')->textInput(['class'=>'form-control extra_km']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'total_kms')->textInput(['readonly'=>true,'class'=>'form-control total_km']) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, true, 'shift_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>



<?php

$script = "
    $('input').change(function(){
        var total = 0;
        var morning_km = parseFloat($('.morning_km').val());
        var evening_km = parseFloat($('.evening_km').val());
        var extra_km = parseFloat($('.extra_km').val());
        if(isNaN(morning_km)){
            morning_km = 0;
        }
        if(isNaN(evening_km)){
            evening_km = 0;
        }
        if(isNaN(extra_km)){
            extra_km = 0;
        }
        total = morning_km + evening_km + extra_km;
        $('.total_km').val(total);
    });";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>