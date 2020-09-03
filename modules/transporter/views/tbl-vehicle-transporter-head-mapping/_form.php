<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Vehicle Transporter Head Mapping');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'create' ? FALSE : TRUE;
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
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?= Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletransporterheadmapping-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_code'), '', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblvehicletransporterheadmapping-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, '', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?= Yii::$app->dropdown->routeVehicleDateWise($model, $form, 'tblvehicletransporterheadmapping-vehicle_code,tblvehicletransporterheadmapping-wef_date', 'route_code', $model->getAttributeLabel('route_code'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-3">
        <?php //Yii::$app->dropdown->depend_dropdown('transporter_payment_head_code', $model, $form, 'tblvehicletransporterheadmapping-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_payment_head_code'), '', $readonly); ?>
        <?= Yii::$app->dropdown->payment_head($model, $form, 'tblvehicletransporterheadmapping-union_code', 'transporter_payment_head_code', $model->getAttributeLabel('transporter_payment_head_code'), FALSE, $readonly); ?> 

    </div>
    <div class="col-sm-3 number-validate">
        <?= $form->field($model, 'amount')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea() ?>
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
//    $(document).ready(function(){
//        $('.default_hide').hide();
//        hide();
//    });
//    $(document).on('change', '#tblvehicletransporterheadmapping-billing_type', function() {  
//        hide();
//    });
//        function hide(){
//            var bill = $('#tblvehicletransporterheadmapping-billing_type').val();
//            if(bill == '0'){
//                 $('.default_hide').show();
//            }else{
//                 $('.default_hide').hide();
//                $('#tblvehicletransporterheadmapping-route_code').val('');
//                $('#tblvehicletransporterheadmapping-route_code').trigger('change');
//                $('#tblvehicletransporterheadmapping-route_code').trigger('select2:select');
//            }
//        }
";
$this->registerJs($script, View::POS_END, 'create-asset-transaction');
?>