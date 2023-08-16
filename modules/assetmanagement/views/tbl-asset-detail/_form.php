<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'disabled';
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('asset_group_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('asset_group_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('asset_code', $model, $form, 'tblassetdetail-asset_group_code', '', $model->getAttributeLabel('asset_code'), 'asset_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('store_location_type'), $readonly, 'store_location_type'); ?>
    </div>
    <!--    <div class="col-sm-2">
    <?php //Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassetdetail-store_location_type', '', $model->getAttributeLabel('store_location_code'), 'store_location_code', $readonly);  ?>
        </div> -->

    <div class="col-sm-2 to_4 to_1 default_hide to_hide">
        <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassetdetail-store_location_type', '', $model->getAttributeLabel('store_location_code'), 'store_location_code', $readonly); ?>
    </div>
    <div class="col-sm-2 to_2 to_3 default_hide to_hide">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassetdetail-union_code', 'to_plant', $model->getAttributeLabel('to_plant'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2 to_2 to_3 default_hide to_hide">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassetdetail-to_plant', 'to_mcc', $model->getAttributeLabel('to_mcc'), FALSE, '', $readonly); ?>
    </div>
    <div class="col-sm-2 to_2 to_3 default_hide to_hide">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassetdetail-to_mcc', 'to_bmc', $model->getAttributeLabel('to_bmc'), FALSE, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2 to_3 default_hide to_hide">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassetdetail-to_bmc', 'to_dcs', $model->getAttributeLabel('to_dcs'), FALSE, '', $readonly, TRUE); ?>
    </div>

    <div class="col-sm-2 hide-serial-no">
        <?= $form->field($model, 'serial_number')->textInput() ?>
    </div>
    <div class="col-sm-2 hide-qty-no <?= $class ?>">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Html::hiddenInput('customer_type', 'SUPPLIER', ['id' => 'customer_type']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('customer_code', $model, $form, 'customer_type', 'form-group col-sm-2', $model->getAttributeLabel('manufacturer_code'), 'manufacturer_code', $readonly, 0, [], FALSE, Yii::t('app', 'Select Vendor')); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'make')->textInput() ?>   
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'capacity')->textInput() ?>   
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'purchase_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2 <?= $class ?>">
        <?= Yii::$app->controls->date($model, $form, 'put_to_use_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'warranty_period')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'maintanance_duration_in_days')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('asset_detail_status', $model, $form, 'form-group', $model->getAttributeLabel('current_status'), false, 'current_status', false); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_verified'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'verification_date', '', FALSE); ?>
    </div>
</div>   
<?= Html::activeHiddenInput($model, 'is_serial_number', ['id' => 'is_serial_number']) ?>
<div class="row">
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
$(document).ready(function(){
$('#tblassetdetail-verification_date').attr('disabled', true);
   $('.hide-serial-no').hide(); 
   $('.hide-qty-no').hide(); 
   if('$type'!='create'){ 
     hideSerialno();     
   }   
    });
     $('.default_hide').hide();
    showHideParams('to');
    getStoreLocationCode('to', 'Yes');
     $('#tblassetdetail-store_location_type').on('change', function(){
        showHideParams('to');
        getStoreLocationCode('to', 'Yes');
    });
 $('#tblassetdetail-to_bmc').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == '2'){
            getStoreLocationCode('to');
        }
    });
    $('#tblassetdetail-to_dcs').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == '3'){
            getStoreLocationCode('to');
        }
    }); 
    function getParamName(select_type){
        var paramName = $('#tblassetdetail-store_location_type').val();
        return paramName;
    }
function showHideParams(type) {
        var selectParam = $('#tblassetdetail-store_location_type').val();
        $('.'+type+'_hide').hide();
        $('.'+type+'_'+selectParam).show();
    }
       

 $('#tblassetdetail-asset_code').on('change',function(){
        hideSerialno();   
   });

    function hideSerialno(){
        var asset_code = $('#tblassetdetail-asset_code').val(); 
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/assetmanagement/tbl-asset-detail/get-asset-is-serial']) . "',
            data: {'asset_code' : asset_code},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                    $('#is_serial_number').val(obj1.is_serial_number);
                    if(obj1.is_serial_number == 0){
                      $('.hide-serial-no').hide(); 
                      $('.hide-qty-no').show(); 
                      $('#tblassetdetail-serial_number').val('');
                    }
                    else{
                      $('.hide-serial-no').show();
                      $('.hide-qty-no').hide(); 
                      $('#tblassetdetail-qty').val('');
                    }
                }
            },
        });
    }
    
        $('#tblassetdetail-is_verified').click(function(){
              isverified_enable();
         });

         function isverified_enable(){
              if($('#tblassetdetail-is_verified').is(':checked')) {
                  $('#tblassetdetail-verification_date').attr('disabled',false);
              } else {
                $('#tblassetdetail-verification_date').attr('disabled',true);
         }
       }
    
     function getStoreLocationCode(select_type, checkEvent = 'No'){
        var selectParam = $('#tblassetdetail-store_location_type').val();
        selectParam = selectParam.toLowerCase();
        if(selectParam == '3' || selectParam == '2'){
            var paramName = selectParam == '3' ? 'dcs' : 'bmc';
            mainVal = $('#tblassetdetail-'+select_type+'_'+paramName).val();
            var dest_type_code = $('#tblassetdetail-store_location_type').val();
            if(mainVal != '' && mainVal != null && mainVal != undefined && dest_type_code != '') {
                var dcs_code = $('#tblassetdetail-'+select_type+'_dcs').val();
                var bmc_code = $('#tblassetdetail-'+select_type+'_bmc').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/assetmanagement/tbl-store-location/get-store-location-code']) . "',
                    data: {'dest_type_code' : dest_type_code, 'dest_type' : paramName, 'bmc_code' : bmc_code, 'dcs_code' : dcs_code},
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success') {
                            if(checkEvent == 'Yes'){
                                $('#tblassetdetail-store_location_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                                    $('#tblassetdetail-store_location_code').val(obj1.sloc_code);
                                    $('#tblassetdetail-store_location_code').trigger('change');
                                    $('#tblassetdetail-store_location_code').trigger('select2:select');
                                });
                            } else {
                                $('#tblassetdetail-store_location_code').val(obj1.sloc_code);
                                $('#tblassetdetail-store_location_code').trigger('change');
                                $('#tblassetdetail-store_location_code').trigger('select2:select');
                            }
                        } else {
                            $('#tblassetdetail-store_location_code').val('');
                            $('#tblassetdetail-store_location_code').trigger('change');
                            $('#tblassetdetail-store_location_code').trigger('select2:select');
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                        }
                    },
                });
            }
        } else {
            $('#'+select_type+'_hide select').val('');
            $('#'+select_type+'_hide select').trigger('change');
            $('#'+select_type+'_hide select').trigger('select2:select');
        }
    }
";
$this->registerJs($script, View::POS_END, 'serial-no-hide');
?>