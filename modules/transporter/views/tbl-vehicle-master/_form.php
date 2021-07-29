<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = 'disabled';
//$model->is_plant=$model->isNewRecord?0:$model->is_plant;
//$nameWarning = 0;
//$codeWarning = 0;
//if (!empty($_POST)) {
//    $nameWarning = $_POST['warning'];
//    $codeWarning = $_POST['code_warning'];
//}
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
//            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Vehicle Detail</h4>
        </div>
        <div class="col-sm-2" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehiclemaster-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
        </div>
        <div class="col-sm-2 <?= $class ?>">
            <?= Yii::$app->dropdown->dropdown('billing_type_code', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('billing_type_code'), false, '', false, false); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('vehicle_type_code', $model, $form, 'form-group col-sm-2', 'Vehicle Type'); ?>
        </div>
        <?= Yii::$app->dropdown->dropdownStatic('vehicle_use_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('vehicle_use_type'), false, 'vehicle_use_type', false); ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('fuel_type_code', $model, $form, 'form-group col-sm-2', 'Fuel Type'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity_code'); ?>        
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'registration_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'applicable_rto')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->field($model, 'pollution_certificate')->dropdownList($list); ?>
            <?php //= $form->field($model, 'pollution_certificate')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'expiry_date', '', FALSE, date('Y-m-d')); ?>
        </div>
        <div class="col-sm-2">
            <?php echo $form->field($model, 'insurance')->dropdownList($list); ?>
            <?php //= $form->field($model, 'insurance')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'rc_book_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'parsing_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'rent')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'average')->textInput() ?>
        </div>
        <!--<div class="col-sm-2">-->
        <?php // Yii::$app->dropdown->dropdownStatic('billing_method', $model, $form, 'form-group', $model->getAttributeLabel('billing_method'), false, 'billing_method', false); ?>
        <!--</div>-->
        <!--<div class="col-sm-2 mt25">-->
            <!--<? Yii::$app->controls->active($model, $form); ?>-->
        <!--</div>-->
        <div class="col-sm-2 mt15">
            <?= $form->field($model, 'billing_with_capacity', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'flag_wef_date', '', FALSE, FALSE); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('billing_qty_flag', $model, $form, 'form-group', $model->getAttributeLabel('billing_qty_flag')); ?>
        </div>
        <div class="col-md-12 padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Driver Detail</h4>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'driver_name')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'driver_contact_no')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'driving_license_number')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'licence_expiry_date', '', FALSE, date('Y-m-d')); ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
     $(document).on('change', '#tblvehiclemaster-transporter_code', function() {  
           $('#tblvehiclemaster-billing_type_code').val('');
          setBillingType();
       });


    function setBillingType(){
        var tr_code = $('#tblvehiclemaster-transporter_code').val();
        if(tr_code != '' && tr_code != null && tr_code != undefined){
             $.ajax({
            type: 'post',
            url:'" . Url::to(['billing-type']) . "',
            data: {'transporter_code':tr_code},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {
                   $('#tblvehiclemaster-billing_type_code').val(obj.data);
//                   $('#tblvehiclemaster-billing_type_code').trigger('change');
//                   $('#tblvehiclemaster-billing_type_code').trigger('select2:select');
                }else{
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Billing Type is not available for Transporter.') . "</span></div></div>', function(result){
                 setTimeout(function(){
                 $('#tblvehiclemaster-transporter_code').focus();},100);
                    }); 
                    $('#tblvehiclemaster-billing_type_code').val('');
                }
            },
            error:function(data){
		
	    }
	});
        }
    }
        $('#tblvehiclemaster-parsing_no').keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
        ";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>


