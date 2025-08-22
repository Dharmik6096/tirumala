<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

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
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('transporter_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('billing_type'), $readonly, 'billing_type', false); ?>

    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->vehicleList($model, $form, 'tblvehicletransporterheadmapping-union_code,tblvehicletransporterheadmapping-billing_type', 'vehicle_code', TRUE, FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2"> 
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletransporterheadmapping-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, '', $readonly); ?>
    </div>
    <div class="col-sm-2 default_hide"> 
        <?= Yii::$app->dropdown->routeVehicleDateWise($model, $form, 'tblvehicletransporterheadmapping-vehicle_code,tblvehicletransporterheadmapping-wef_date', 'route_code', $model->getAttributeLabel('route_code'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-2">
        <?php //Yii::$app->dropdown->depend_dropdown('transporter_payment_head_code', $model, $form, 'tblvehicletransporterheadmapping-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_payment_head_code'), '', $readonly);  ?>
        <?= Yii::$app->dropdown->payment_head($model, $form, 'tblvehicletransporterheadmapping-union_code', 'transporter_payment_head_code', $model->getAttributeLabel('transporter_payment_head_code'), FALSE, $readonly); ?> 

    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'amount')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    $(document).ready(function() {
        $('.field-tblvehicletransporterheadmapping-transporter_code').addClass('disabled no_pointer');
    });
    $(document).ready(function(){
        $('.default_hide').hide();
        hide();
    });
    $(document).on('change', '#tblvehicletransporterheadmapping-billing_type', function() {  
        hide();
    });
        function hide(){
            var bill = $('#tblvehicletransporterheadmapping-billing_type').val();
            if(bill == '0'){
                 $('.default_hide').show();
            }else{
                 $('.default_hide').hide();
                $('#tblvehicletransporterheadmapping-route_code').val('');
                $('#tblvehicletransporterheadmapping-route_code').trigger('change');
                $('#tblvehicletransporterheadmapping-route_code').trigger('select2:select');
            }
        }
        
        $('#tblvehicletransporterheadmapping-vehicle_code').on('change', function(){
            var vehicle_code = $(this).val();
             if(setData(vehicle_code)){
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['tbl-vehicle-master/get-vehicle-detail']) . "',    
                    data: 'vehicle_code='+vehicle_code,
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success' && obj1.data){
                            $('#tblvehicletransporterheadmapping-transporter_code').val(obj1.data.transporter_code).trigger('change').trigger('select2:select');
                        }
                    }
                });
            } else {
                $('#tblvehicletransporterheadmapping-transporter_code').val(null).trigger('change');
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
$this->registerJs($script, View::POS_END, 'create-asset-transaction');
?>