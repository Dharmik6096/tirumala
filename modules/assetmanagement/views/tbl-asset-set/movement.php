<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::$app->label->title('create', 'Asset SAP Code Movement');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'id' => 'set_movement_form',
                        'field-class' => 'form-group col-sm-6'
                    ],
                    'validateOnBlur' => FALSE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>   
        <div class="single_entry_area col-sm-12 padding-left-0 padding-right-0">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <div class="col-sm-3">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE, FALSE); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('from_type'), false, 'from_type'); ?>
                </div>
                <div class="col-sm-2 from_warehouse from_plant default_hide from_hide">
                    <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassettransaction-from_type', '', $model->getAttributeLabel('from_dest'), 'from_dest', false); ?>
                </div>
                <div class="col-sm-2 from_mcc from_dsk default_hide from_hide">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassettransaction-union_code', 'from_plant', $model->getAttributeLabel('from_plant')); ?>
                </div>
                <div class="col-sm-2 from_mcc from_dsk default_hide from_hide">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassettransaction-from_plant', 'from_mcc', $model->getAttributeLabel('from_mcc')); ?>
                </div>
                <div class="col-sm-2 default_hide from_hide">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassettransaction-from_mcc', 'from_bmc', $model->getAttributeLabel('from_bmc')); ?>
                </div>
                <div class="col-sm-2 from_dsk default_hide from_hide">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassettransaction-from_bmc', 'from_dcs', $model->getAttributeLabel('from_dcs'), FALSE, '', FALSE, TRUE); ?>
                </div>
                <div class="col-sm-2 from_warehouse from_plant from_dsk from_mcc default_hide from_hide">
                    <?= Yii::$app->dropdown->depend_dropdown('asset_set', $model, $form, 'tblassettransaction-from_dest', '', $model->getAttributeLabel('sap_code'), 'sap_code', false); ?>
                </div>
            </div>

        </div>
        <div id="equipment">

        </div>
        <div id="todest">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <div class="col-sm-2 dis_dcs">
                    <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('to_type'), false, 'to_type'); ?>
                </div>
                <div class="col-sm-2 to_warehouse to_plant default_hide to_hide">
                    <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassettransaction-to_type', '', $model->getAttributeLabel('to_dest'), 'to_dest', false); ?>
                </div>
                <div class="col-sm-2 to_mcc to_dsk default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassettransaction-union_code', 'to_plant', $model->getAttributeLabel('to_plant')); ?>
                </div>
                <div class="col-sm-2 to_mcc to_dsk default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassettransaction-to_plant', 'to_mcc', $model->getAttributeLabel('to_mcc')); ?>
                </div>
                <div class="col-sm-2 default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassettransaction-to_mcc', 'to_bmc', $model->getAttributeLabel('to_bmc')); ?>
                </div>
                <div class="col-sm-2 to_dsk default_hide to_hide">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassettransaction-to_bmc', 'to_dcs', $model->getAttributeLabel('to_dcs'), FALSE, '', FALSE, TRUE); ?>
                </div>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', false, false, false); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'in_ward', ['checkboxTemplate' => "<div class='checkbox mt25'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
            </div> 
            <div class="col-sm-6">
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>

            <div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Save'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['movement']),
                            'beforeSend' => new JsExpression("function(data){
                                           // $('#loadercontent').show();
                                          //  $('#pageloader').show();
                                        }"),
                            'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                              //  $(\'#loadercontent\').hide();
                                                              //  $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                     $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                     bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }
                                                 }'),
                        ],
                        'options' => ['class' => 'btn btn-default',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$script = "
     $('.default_hide').hide();
     $('#todest').hide();    
    $('#tblassettransaction-from_type').on('change', function(){
        showHideParams('from');
        getStoreLocationCode('from', 'Yes');
    });
    $('#tblassettransaction-to_type').on('change', function(){
        showHideParams('to');
        getStoreLocationCode('to', 'Yes');
         getDataMccData();
    });

    function showHideParams(type) {
        var selectParam = $('#tblassettransaction-'+type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        $('.'+type+'_hide').hide();
        $('.'+type+'_'+selectParam).show();
    }
    
    $('#tblassettransaction-from_mcc').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == 'mcc'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassettransaction-from_dcs').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == 'dcs'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassettransaction-to_mcc').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == 'mcc'){
            getStoreLocationCode('to');
        }
    });
    $('#tblassettransaction-to_dcs').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == 'dcs'){
            getStoreLocationCode('to');
        }
    });
    function getParamName(select_type){
        var selectParam = $('#tblassettransaction-'+select_type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        var paramName = selectParam == 'dsk' ? 'dcs' : selectParam;
        return paramName;
    }
    function getStoreLocationCode(select_type, checkEvent = 'No'){
        var selectParam = $('#tblassettransaction-'+select_type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        if(selectParam == 'dsk' || selectParam == 'mcc'){
            var paramName = selectParam == 'dsk' ? 'dcs' : selectParam;
            mainVal = $('#tblassettransaction-'+select_type+'_'+paramName).val();
            var dest_type_code = $('#tblassettransaction-'+select_type+'_type').val();
            if(mainVal != '' && mainVal != null && mainVal != undefined && dest_type_code != '') {
                var dcs_code = $('#tblassettransaction-'+select_type+'_dcs').val();
                var mcc_code = $('#tblassettransaction-'+select_type+'_mcc').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/assetmanagement/tbl-store-location/get-store-location-code']) . "',
                    data: {'dest_type_code' : dest_type_code, 'dest_type' : selectParam, 'mcc_code' : mcc_code, 'dsk_code' : dcs_code},
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success') {
                            if(checkEvent == 'Yes'){
                                $('#tblassettransaction-'+select_type+'_dest').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                                    $('#tblassettransaction-'+select_type+'_dest').val(obj1.sloc_code);
                                    $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                                    $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                                });
                            } else {
                                $('#tblassettransaction-'+select_type+'_dest').val(obj1.sloc_code);
                                $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                                $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                            }
                        } else {
                            $('#tblassettransaction-'+select_type+'_dest').val('');
                            $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                            $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                        }
                    },
                });
            }
        } else {
            $('#'+select_type+'_hide select').val('');
            $('#'+select_type+'_hide select').trigger('change');
            $('#'+select_type+'_hide select').trigger('select2:select');
            if(select_type=='from'){
            $('#todest').hide(); 
            $('#equipment').html('');
            }
        }
    }
    $('#tblassettransaction-sap_code').on('change', function(){
    var asset_set_code = $(this).val();
    if(asset_set_code != '' && asset_set_code!=null){
      getSelectedData();
        $.ajax({
                type: 'post',
                    url: '" . Url::to(['/assetmanagement/tbl-asset-set/set-detail']) . "',
                data: {'asset_set_code' : asset_set_code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                   $('#equipment').html(data);
                   $('#todest').show(); 
                   $('#loadercontent').hide();
                   $('#pageloader').hide();
                },
            });
      }else{
   $('#equipment').html(''); 
     $('#todest').hide(); 
}
    });  
    function getSelectedData() {
        var selectParam = $('#tblassettransaction-from_type option:selected').text();
        selectParam = selectParam.toLowerCase();
         var fromType = $('#tblassettransaction-from_type').val();
         var fromPlant = $('#tblassettransaction-from_plant').val();
         var fromMcc = $('#tblassettransaction-from_mcc').val();
         var fromDcs = $('#tblassettransaction-from_dcs').val();
         $('#tblassettransaction-to_type').val('');
         $('#tblassettransaction-to_type').trigger('change');
         $('.disa_drop').removeClass('disabled');
         $('.dis_dcs').removeClass('disabled');
        if(selectParam == 'dsk'){
             $('#tblassettransaction-to_type').val(2);
             $('#tblassettransaction-to_type').trigger('change');
                   $('#tblassettransaction-to_plant').val(fromPlant);
                   $('#tblassettransaction-to_plant').trigger('change');
                   $('#tblassettransaction-to_plant').trigger('select2:select');
               $('#tblassettransaction-to_mcc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                   $('#tblassettransaction-to_mcc').val(fromMcc);
                   $('#tblassettransaction-to_mcc').trigger('change');
                   $('#tblassettransaction-to_mcc').trigger('select2:select');
               });
               $('.disa_drop').addClass('disabled');
               $('.dis_dcs').addClass('disabled');
        }
        
    }
    
    function getDataMccData() {
        $('.disa_drop').removeClass('disabled');
        var selectParam = $('#tblassettransaction-from_type option:selected').text();
        selectParam = selectParam.toLowerCase();
         var fromType = $('#tblassettransaction-from_type').val();
         var fromPlant = $('#tblassettransaction-from_plant').val();
         var fromMcc = $('#tblassettransaction-from_mcc').val();
         var fromDcs = $('#tblassettransaction-from_dcs').val();
         if(selectParam == 'mcc'){
            var selectToParam = $('#tblassettransaction-to_type option:selected').text();
            selectToParams = selectToParam.toLowerCase();
                if(selectToParams == 'dsk'){
                   $('#tblassettransaction-to_plant').val(fromPlant);
                   $('#tblassettransaction-to_plant').trigger('change');
                   $('#tblassettransaction-to_plant').trigger('select2:select');
                      $('#tblassettransaction-to_mcc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                         $('#tblassettransaction-to_mcc').val(fromMcc);
                         $('#tblassettransaction-to_mcc').trigger('change');
                         $('#tblassettransaction-to_mcc').trigger('select2:select');
                       });
                        $('.disa_drop').addClass('disabled');
                }
        }
    }

";
$this->registerJs($script, View::POS_END, 'create-asset-transaction');
?>