<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use app\modules\usermanagement\components\GhostHtml;

$readonly = $type == 'issue' ? FALSE : TRUE;
$title = $type == 'issue' ? 'Good Issue' : 'Good Receipt';
$disable = $readonly ? 'disabled' : '';
$list = array('0' => 'No', '1' => 'Yes');
?>
<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'good-issue-form'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?php echo $title; ?> Details</h4>
        </div>
        <div class="col-md-10">
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
            </div>
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->dropdownStatic('org_type', $model, $form, 'form-group', $model->getAttributeLabel('type'), false, 'type', false); ?>
            </div>
            <div class="col-sm-2 create_fields" id="plant">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductstockadjustment-union_code', 'plant_code', $model->getAttributeLabel('plant')); ?>
            </div>
            <div class="col-sm-2 create_fields" id="mcc">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductstockadjustment-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc')); ?>
            </div>
            <div class="col-sm-2" id="bmc">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductstockadjustment-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc')); ?>
            </div>
            <div class="col-sm-2" id="dcs">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblproductstockadjustment-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs'), FALSE, '', FALSE, TRUE); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'remarks')->textInput()->label(Yii::t('app', 'Remarks')) ?>
            </div>
            <?= Html::activeHiddenInput($model, 'code', ['id' => 'code']) ?>
            <?php
            if ($type == 'issue') {
                echo Html::activeHiddenInput($model, 'adjustment_type', ['id' => 'adjustment_type', 'value' => 'Good Issue']);
            } else {
                echo Html::activeHiddenInput($model, 'adjustment_type', ['id' => 'adjustment_type', 'value' => 'Good Receipt']);
            }
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?php echo $title; ?> Txn Details</h4>
        </div>
        <div class="col-sm-3"> 
            <?= Html::hiddenInput('x_col3', '2', ['id' => 'x_col3']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('product', $txModel, $form, 'tblproductstockadjustment-union_code,x_col3', 'form-group col-sm-2 padding-right-5 padding-left-0', $txModel->getAttributeLabel('product_code'), 'product_code'); ?>
        </div>
        <?php if ($batchNoWiseInventory == 1) { ?>
            <div class="col-sm-2 create_fields" id="old_sap_batch_no">
                <?= Yii::$app->dropdown->productBatchLstSixMonth($txModel, $form, 'tblproductstockadjustment-type,code,tblproductstockadjustmenttransaction-product_code', 'sap_batch_no', $txModel->getAttributeLabel('sap_batch_no'), FALSE); ?> 
            </div>
        <?php }
        ?>
        <div class=" col-sm-2 reset_field unit disabledDiv">
            <?= Yii::$app->dropdown->dropdown('unit_code', $txModel, $form, 'form-group col-sm-2', $txModel->getAttributeLabel('unit'), FALSE, 'unit'); ?>    
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'stock')->textInput(['readonly' => TRUE])->label(Yii::t('app', 'Available Stock')) ?>
        </div>
        <div class="col-sm-2 create_fields reset_field qty-validate">
            <?= $form->field($txModel, 'qty')->textInput()->label(Yii::t('app', 'Quantity')) ?>
        </div>
        <?php if ($type == 'issue') { ?>
            <div class="col-sm-2 create_fields reset_field qty-validate">
                <?= Yii::$app->dropdown->dropdownStatic('product_stock_issue_reason', $txModel, $form, 'form-group', true, false, 'reason', false); ?>
            </div>
        <?php }
        ?>
        <div class="col-sm-2 create_fields reset_field">
            <?= $form->field($txModel, 'remarks')->textInput()->label(Yii::t('app', 'Remarks')) ?>
        </div>
        <?php if ($type != 'issue' && $batchNoWiseInventory == 1) { ?>
            <div class="col-sm-2 create_fields reset_field">
                <label class="d-flex">
                    <?= Html::checkbox('other', false, ['id' => 'other', 'class' => 'checkbox']) ?> Other
                </label>
            </div>
            <div class="col-sm-2 create_fields reset_field" id="new_sap_batch_no" style="display:none;">
                <?= $form->field($txModel, 'sap_batch_no')->textInput(['class'=>'form-control','disabled'=>'disabled', 'id'=>'sap_batch_no'])->label(Yii::t('app', 'Sap Batch No')) ?>
            </div>
        <?php }
        ?>
        <div class="col-sm-2 padding_top_20 shortcut-main">
            <?=
            Html::a(Yii::t('app', 'Add'), 'javascript:void(0)', ['class' => 'btn btn-primary add-asset-record disabled no_pointer', 'id' => 'add_product'])
            ?>
        </div>        
    </div>
</div>
<div class="col-sm-12">
    <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
        <thead>
            <tr>
                <th>Product</th>
                <?php
                if($batchNoWiseInventory == 1){ ?>
                    <th>Sap Batch No</th>
                <?php
                } ?>
                <th>Unit</th>
                <th>Stock</th>
                <th>Quantity</th>
                <?php if ($type == 'issue') { ?>
                    <th>Reason</th>
                <?php }
                ?>
                <th>Remark</th>
                <th><?= Yii::t('app', 'Action') ?></th>
            </tr> 
        </thead>
        <tbody id="product_list">

        </tbody>
    </table>
</div>
<div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Save'),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create']),
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
                             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                        }
                }'),
            ],
            'options' => ['class' => 'btn btn-default btn-save-txn disabled no_pointer',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>
        <?php //Yii::$app->controls->custombutton(Yii::t('app', ucfirst('reset')), 'asset-transaction'); ?>
        <?php //Yii::$app->controls->cancel($model); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$userType = Yii::$app->session->get('UserType');

$script = "
var userType = '$userType';
var goodType = '$type';
var batchNoWiseInventory = '$batchNoWiseInventory';
$('#other').on('change', function(){
    if($('#other').prop('checked')){
        $('#old_sap_batch_no').css('display','none');
        $('#new_sap_batch_no').css('display','block');
        $('#tblproductstockadjustmenttransaction-sap_batch_no').attr('disabled',true);
        $('#sap_batch_no').attr('disabled',false);
    } else {
        $('#new_sap_batch_no').css('display','none');
        $('#old_sap_batch_no').css('display','block');
        $('#sap_batch_no').attr('disabled',true);
        $('#tblproductstockadjustmenttransaction-sap_batch_no').attr('disabled',false);
    }
});
$('#tblproductstockadjustment-type').on('change', function(){
    $('#tblproductstockadjustment-mcc_plant_code').val('');
    $('#tblproductstockadjustment-mcc_plant_code').trigger('select2:select');
    $('#tblproductstockadjustment-mcc_plant_code').trigger('change');
    setCode();
    if($(this).val()=='MCC'){
        $('#mcc').show();
        $('#bmc').hide(); 
        $('#dcs').hide(); 
    }else if($(this).val()=='BMC'){
        $('#mcc').show(); 
        $('#bmc').show();
        $('#dcs').hide();
    }else if($(this).val()=='DCS'){
        $('#mcc').show(); 
        $('#bmc').show();
        $('#dcs').show();
    }else{
        $('#mcc').hide(); 
        $('#bmc').hide(); 
        $('#dcs').hide(); 
    }
});
$(document).ready(function(){
    var append_no = 0; 
//    $('#mcc').hide(); 
    $('#bmc').hide(); 
    $('#dcs').hide();
    if(userType == 5){
        $('#tblproductstockadjustment-type').val('MCC');
        $('#tblproductstockadjustment-type').trigger('select2:select');
        $('#tblproductstockadjustment-type').trigger('change');
        $('.field-tblproductstockadjustment-type').addClass('disabledDiv');
    }
    $('#add_product').on('click', function(){
        var err = '';
        var sap_batch_no = '';
        if(batchNoWiseInventory == 1){
            $('.disable_fields').addClass('disabled_div');
            if($('#other').prop('checked')){
                sap_batch_no = $('#sap_batch_no').val();
            } else {
                sap_batch_no = $('#tblproductstockadjustmenttransaction-sap_batch_no option:selected').val();
            }
        }
        var product_code = $('#tblproductstockadjustmenttransaction-product_code option:selected').val();
        var unit = $('#tblproductstockadjustmenttransaction-unit option:selected').val();
        var stock = $('#tblproductstockadjustmenttransaction-stock').val();
        var product_qty = $('#tblproductstockadjustmenttransaction-qty').val();
        
        if(product_code == ''){
            err += '\\nProduct Code can not be Blank.';
        }
        if(sap_batch_no == '' && batchNoWiseInventory == 1){
            err += '\\nSap Batch No can not be Blank.';
        }
        if(unit == ''){
            err += '\\nUnit can not be Blank.';
        }
        if(stock <= 0){
            err += '\\nStock not available.';
        }
        if(product_qty <= 0){
            err += '\\Please enter quantity getter then 0.';
        }
        if(err == ''){
            var tr_class;
            if(batchNoWiseInventory == 1){
                var change_sap_batch_no = sap_batch_no.replace('/','-'); 
                tr_class = product_code+'_'+change_sap_batch_no;
            } else {
                tr_class = product_code;
            }            
            if($('.'+tr_class).length > 0){
                if(batchNoWiseInventory == 1){
                    err += '\\nAlready exist sap batch no. Please select unother sap batch no.';
                } else {
                    err += '\\nAlready exist product. Please select unother product.';
                }
                bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+err+'</div></div>');
                return false;
            } else {
                var product_name = $('#tblproductstockadjustmenttransaction-product_code option:selected').text();
                var sap_batch_name = '';
                if(batchNoWiseInventory == 1){
                    if($('#other').prop('checked')){
                        sap_batch_name = $('#sap_batch_no').val();
                    } else {
                        sap_batch_name = $('#tblproductstockadjustmenttransaction-sap_batch_no option:selected').text();
                    }
                }
                var unit_name = $('#tblproductstockadjustmenttransaction-unit option:selected').val();
                var reason = $('#tblproductstockadjustmenttransaction-reason option:selected').val();
                var remarks = $('#tblproductstockadjustmenttransaction-remarks').val();
                var append_data = '<tr class='+tr_class+'>';
                append_data += '<td>'+product_name+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][product_code]\' value='+product_code+'></td>';
                if(batchNoWiseInventory == 1){
                    append_data += '<td>'+sap_batch_name+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][sap_batch_no]\' value='+sap_batch_no+'></td>';
                }
                append_data += '<td>'+unit_name+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][unit]\' value='+unit+'></td>';
                append_data += '<td>'+stock+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][stock]\' value=\''+stock+'\'></td>';
                append_data += '<td>'+product_qty+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][qty]\' value=\''+product_qty+'\'></td>';
                if(goodType == 'issue'){
                    append_data += '<td>'+reason+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][reason]\' value=\''+reason+'\'></td>';
                }
                append_data += '<td>'+remarks+'<input type=\'hidden\' name=\'TblProductStockAdjustmentTransaction['+tr_class+'][remarks]\' value=\''+remarks+'\'></td>';
                append_data += '<td class=\'pb8\'><a href=\'javascript:void(0);\' class=\'remove_product btn btn-default btn-raised\' title=\'Remove\'>Remove</a></td>';
                append_data += '</tr>';
                $('#product_list').append(append_data);
                $('#tblproductstockadjustmenttransaction-sap_batch_no').val('');
                $('#tblproductstockadjustmenttransaction-stock').val('');
                $('#tblproductstockadjustmenttransaction-product_code').val(null).trigger('change');
                $('#tblproductstockadjustmenttransaction-sap_batch_no').val(null).trigger('change');
                $('#tblproductstockadjustmenttransaction-unit').val(null).trigger('change');
//                $('#tblproductstockadjustmenttransaction-remarks').val('');
                $('#tblproductstockadjustmenttransaction-qty').val('');
                $('#tblproductstockadjustmenttransaction-reason').val(null).trigger('change');
                $('#tblproductstockadjustmenttransaction-remarks').val('');
                $('.btn-save-txn').removeClass('disabled no_pointer');
            }
        } else {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+err+'</div></div>');
        }
    });
    $(document).on('click', '.remove_product', function () {
        $(this).closest('tr').remove();
    });


    $('#tblproductstockadjustment-mcc_plant_code').on('change', function(){
        setCode();
        addBtnEnable();
    });
    $('#tblproductstockadjustmenttransaction-product_code').on('change', function(){
        setUnit();
        getAvailableStock();
        addBtnEnable();
    });
    $('#tblproductstockadjustment-bmc_code').on('change', function(){
        setCode();
    });
    $('#tblproductstockadjustment-dcs_code').on('change', function(){
        setCode();
    });
    $('#tblproductstockadjustment-plant_code').on('change', function(){
        addBtnEnable();
    });
    $('#tblproductstockadjustmenttransaction-unit').on('change', function(){
        addBtnEnable();
    });
    $('#tblproductstockadjustmenttransaction-stock').on('change', function(){
        addBtnEnable();
    });
    $('#tblproductstockadjustmenttransaction-qty').on('change', function(){
        addBtnEnable();
    });
    $('#tblproductstockadjustmenttransaction-sap_batch_no').on('change', function(){
        getAvailableStock();
        addBtnEnable();
    });
    $('#sap_batch_no').on('change', function(){
        getAvailableStock();
        addBtnEnable();
    });


});
function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    }else {
        return false;
    }
} 
function setUnit(){
    var product = $('#tblproductstockadjustmenttransaction-product_code').val();
     if(setData(product)){
         $.ajax({
                type: 'post',
                url:'" . Url::to(['get-unit']) . "',
                data: {'product':product},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        $('#tblproductstockadjustmenttransaction-unit').val(obj.unit);
                        $('#tblproductstockadjustmenttransaction-unit').trigger('select2:select');
                        $('#tblproductstockadjustmenttransaction-unit').trigger('change');
                    }
                },
                error:function(data){

                }
            });
    } 
    
}
function setCode(){
   var type =$('#tblproductstockadjustment-type').val();
   var mcc =$('#tblproductstockadjustment-mcc_plant_code').val();
   var bmc =$('#tblproductstockadjustment-bmc_code').val();
   var dcs =$('#tblproductstockadjustment-dcs_code').val();
    if(type =='MCC'){
        $('#code').val(mcc);
         $('#code').trigger('change');
    }else if(type =='BMC'){
        $('#code').val(bmc);
         $('#code').trigger('change');
    }else if(type =='DCS'){
        $('#code').val(dcs);
         $('#code').trigger('change');
    }
}
function getAvailableStock(){
    var type = $('#tblproductstockadjustment-type').val();
    var code = $('#code').val();
    var product = $('#tblproductstockadjustmenttransaction-product_code').val();
    var union = $('#tblproductstockadjustment-union_code').val();
    var batch_no = $('#tblproductstockadjustmenttransaction-sap_batch_no').val();

     if(setData(type) && setData(code) && setData(product)){
         $.ajax({
                type: 'post',
                url:'" . Url::to(['get-available-stock']) . "',
                data: {'product':product,'from_type':type,'from_code':code,'union_code':union,'batch_no':batch_no},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success')
                    {
                        $('#tblproductstockadjustmenttransaction-stock').val(obj.stock);
                    }
                },
                error:function(data){

                }
            });
    } 

}
function addBtnEnable(){
    var type = $('#tblproductstockadjustment-type').val();
    var plant_code = $('#tblproductstockadjustment-plant_code').val();
    var mcc_plant_code = $('#tblproductstockadjustment-mcc_plant_code').val();    
    var product_code = $('#tblproductstockadjustmenttransaction-product_code').val();
    var sap_batch_no = $('#tblproductstockadjustmenttransaction-sap_batch_no').val();
    var unit_code = $('#tblproductstockadjustmenttransaction-unit').val();
    var stock = $('#tblproductstockadjustmenttransaction-stock').val();
    var qty = $('#tblproductstockadjustmenttransaction-qty').val();

    if(type != '' && plant_code != '' && mcc_plant_code != '' && product_code != '' && unit_code != '' && qty != '' && stock != '') {
        $('.add-asset-record').removeClass('disabled no_pointer');
    } else {
        $('.add-asset-record').addClass('disabled no_pointer');
    }
}
";
$this->registerJs($script, View::POS_END, 'good-issue');
?>