<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? 'default_hide' : '';
$type == 'create' ? ($url = ['create']) : ($url = ['update', 'id' => $model->asset_set_code]);
$class_dcs = '';
$btn = $type == 'create' ? 'create' : 'update';
?>
<div class="panel-body">
    <?php
    $form = ActiveForm::begin(['options' => [
                    'id' => 'set_movement_form',
                    'field-class' => 'form-group col-sm-6'
                ],
                'validateOnBlur' => FALSE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>   
    <?php echo $form->errorSummary($model); ?>
    <div class="single_entry_area col-sm-12 padding-left-0 padding-right-0">
        <div class="col-sm-12 padding-left-0 padding-right-0">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE, $readonly); ?>
            </div>
            <?php
            if ($type == 'edit') {
                $model->from_type = $model->store_location_type;
                $model->from_dest = $model->store_location_code;
                $model->status = $model->status == 2 ? ($model->status = 1) : 0;
                $class_dcs = $model->store_location_type == 3 ? 'default_hide' : '';
            }
            ?>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('from_type'), $readonly, 'from_type'); ?>
            </div>
            <div class="col-sm-2 from_warehouse from_plant from_hide <?= $class ?>">
                <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassetset-from_type', '', $model->getAttributeLabel('from_dest'), 'from_dest', $readonly); ?>
            </div>
            <div class="col-sm-2 from_bmc from_dcs default_hide from_hide">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassetset-union_code', 'from_plant', $model->getAttributeLabel('from_plant')); ?>
            </div>
            <div class="col-sm-2 from_bmc from_dcs default_hide from_hide">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassetset-from_plant', 'from_mcc', $model->getAttributeLabel('from_mcc')); ?>
            </div>
            <div class="col-sm-2 from_bmc from_dcs default_hide from_hide">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassetset-from_mcc', 'from_bmc', $model->getAttributeLabel('from_bmc')); ?>
            </div>
            <div class="col-sm-2 default_hide from_hide">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassetset-from_bmc', 'from_dcs', $model->getAttributeLabel('from_dcs'), FALSE, '', FALSE, TRUE); ?>
            </div>
            <div class="col-sm-2 from_bmc from_plant from_warehouse from_hide <?= $class ?>">
                <?= $form->field($model, 'sap_code')->textInput() ?>
            </div>
            <div class="col-sm-2 from_bmc from_plant from_warehouse from_hide <?= $class ?><?= $class_dcs ?>">
                <?= $form->field($model, 'status', ['checkboxTemplate' => "<div class='checkbox mt25'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox()->label('In-Use'); ?>
            </div> 
        </div>

    </div>
    <div id='dcs_view'></div>
    <div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', $btn),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to($url),
                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                }else{
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
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


<?php
$script = "
     $('.default_hide').hide();
    $('#tblassetset-from_type').on('change', function(){
        showHideParams('from');
        getStoreLocationCode('from', 'Yes');
    });
    $('#tblassetset-to_type').on('change', function(){
        showHideParams('to');
        getStoreLocationCode('to', 'Yes');
    });

    function showHideParams(type) {
        var selectParam = $('#tblassetset-'+type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        $('.'+type+'_hide').hide();
        $('.'+type+'_'+selectParam).show();
        $('#dcs_view').hide();
    }
    
    $('#tblassetset-from_bmc').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == 'dcs'){
        getDcsData();
        }
        if(selectedParamName == 'bmc'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassetset-from_dcs').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == 'dcs'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassetset-to_mcc').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == 'bmc'){
            getStoreLocationCode('to');
        }
    });
    $('#tblassetset-to_dcs').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == 'dcs'){
            getStoreLocationCode('to');
        }
    });
    function getParamName(select_type){
        var selectParam = $('#tblassetset-'+select_type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        var paramName = selectParam == 'dcs' ? 'dcs' : selectParam;
        return paramName;
    }
    function getStoreLocationCode(select_type, checkEvent = 'No'){
        var selectParam = $('#tblassetset-'+select_type+'_type option:selected').text();
        selectParam = selectParam.toLowerCase();
        if(selectParam == 'dcs' || selectParam == 'bmc'){
            var paramName = selectParam == 'dcs' ? 'dcs' : selectParam;
            mainVal = $('#tblassetset-'+select_type+'_'+paramName).val();
            var dest_type_code = $('#tblassetset-'+select_type+'_type').val();
            if(mainVal != '' && mainVal != null && mainVal != undefined && dest_type_code != '') {
                var dcs_code = $('#tblassetset-'+select_type+'_dcs').val();
                var bmc_code = $('#tblassetset-'+select_type+'_bmc').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/assetmanagement/tbl-store-location/get-store-location-code']) . "',
                    data: {'dest_type_code' : dest_type_code, 'dest_type' : selectParam, 'bmc_code' : bmc_code, 'dcs_code' : dcs_code},
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success') {
                            if(checkEvent == 'Yes'){
                                $('#tblassetset-'+select_type+'_dest').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                                    $('#tblassetset-'+select_type+'_dest').val(obj1.sloc_code);
                                    $('#tblassetset-'+select_type+'_dest').trigger('change');
                                    $('#tblassetset-'+select_type+'_dest').trigger('select2:select');
                                });
                            } else {
                                $('#tblassetset-'+select_type+'_dest').val(obj1.sloc_code);
                                $('#tblassetset-'+select_type+'_dest').trigger('change');
                                $('#tblassetset-'+select_type+'_dest').trigger('select2:select');
                            }
                        } else {
                            $('#tblassetset-'+select_type+'_dest').val('');
                            $('#tblassetset-'+select_type+'_dest').trigger('change');
                            $('#tblassetset-'+select_type+'_dest').trigger('select2:select');
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
       
    function getDcsData(){
    var bmc_code=$('#tblassetset-from_bmc').val();
        if(bmc_code != '' && bmc_code != null && bmc_code != undefined){
            $.ajax({
            type: 'post',
            url:'" . Url::to(['get-dcs-data']) . "',
            data: {'from_bmc':bmc_code},
           beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#dcs_view').html(data);
                   $('#dcs_view').show();              
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
	});
        }
    }
";
$this->registerJs($script, View::POS_END, 'create-asset-transaction');
?>