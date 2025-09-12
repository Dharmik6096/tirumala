<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$url = $type == 'create' ? ['create'] : ['update', 'id' => $model->bmc_dispatch_stock_code];
$label = $type == 'create' ? 'Add' : 'Update';
$disabled = ($model->bmc_code != '') ? TRUE : FALSE;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'bmc-dispatch-stock-from'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary([$model]); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Physical Stock Punching</h4>
        </div>
        <div class="col-md-8 micro_form padding-bottom-20">
            <div class="col-sm-2 filldata">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcdispatchstock-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $disabled); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcdispatchstock-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, '', $disabled); ?>
            </div>  
            <div class="col-sm-2 filldata">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcdispatchstock-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE, '', '', $disabled); ?>
            </div>  
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, false, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift_code', true, $readonly, 'from_shift_code'); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, false, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift_code', true, $readonly, 'to_shift_code'); ?>
            </div>
        </div>

        <div class="col-lg-4">
            <h5 class="panel-heading mb15"><?= Yii::t('app', 'Purchase Information') ?></h5>
            <div id="purchase-detial">
                <table class="table tab-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Milk Type</th>
                            <th>Quality Type</th>
                            <th>Silo No.</th>                 
                            <th>Purchase Qty</th>
                            <th>Opening Balance</th>    
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Physical Stock Punching Details</h4>
        </div>
        <div class="col-sm-2 reset_field">
            <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmcdispatchstock-module_name']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $model, $form, 'tblbmcdispatchstock-bmc_code,tblbmcdispatchstock-module_name', 'form-group col-sm-4', $model->getAttributeLabel('bmc_silos_info_code'), '', $readonly, '', '', FALSE, '', TRUE); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'fat')->textInput(['class' => 'two-decimal-validate']) ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'snf')->textInput(['class' => 'two-decimal-validate']) ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'water')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field"> 
            <?= Yii::$app->dropdown->dropdown('qty_diff_type', $model, $form, '', true, $readonly, 'qty_diff_type_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'opening_bal')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'purchase_qty')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'balance_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'qty_diff')->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group ">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', $label),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to($url),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                $("#transactions-detial").html(data.data);
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $("#bmc-dispatch-stock-from .reset_field input").val("");
                                                                    $("#bmc-dispatch-stock-from .reset_field select").val("");
                                                                    $("#bmc-dispatch-stock-from .reset_field textarea").val("");
                                                                    $("#tblbmcdispatchstock-bmc_silos_info_code").change();
                                                                    $("#tblbmcdispatchstock-milk_type_code").change();
                                                                    $("#tblbmcdispatchstock-milk_quality_type_code").change();
                                                                    $("#tblbmcdispatchstock-qty_diff_type_code").change();    
                                                                   $(".panel-body").scrollTop(0);
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }else{
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $(".error-summary").show();
                                                                    $.each(data, function(key, val) {
                                                                    $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<div class="col-md-12 padding_10_0 theme-box ">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
        <h4 class="theme-box-heading"><?= Yii::t('app', 'BMC Dispatch Stock Detail') ?></h4>
    </div>
    <div id="transactions-detial">

    </div>
</div>
<?php
$script = "
$(document).ready(function() {

    calculateQtyDiff();

    $(document).on('change', '#tblbmcdispatchstock-opening_bal, #tblbmcdispatchstock-purchase_qty, #tblbmcdispatchstock-balance_qty', function() {
        calculateQtyDiff();
    });

    function calculateQtyDiff(){
        var openingBal = parseFloat($('#tblbmcdispatchstock-opening_bal').val());
        var purchaseQty = parseFloat($('#tblbmcdispatchstock-purchase_qty').val());
        var balanceQty = parseFloat($('#tblbmcdispatchstock-balance_qty').val());
        var qty_diff = $('#tblbmcdispatchstock-qty_diff');
        if (!isNaN(openingBal) && !isNaN(purchaseQty) && !isNaN(balanceQty)) {
            var qtyDifference = openingBal + purchaseQty - balanceQty;
            qtyDif = Math.abs(qtyDifference);
            $('#tblbmcdispatchstock-qty_diff').val(qtyDif);
        }
    }
});

    $(document).on('change', '#tblbmcdispatchstock-bmc_code', function () {
        setDatePurchaseInfo();
    });
    $(document).on('change', '#tblbmcdispatchstock-milk_type_code, #tblbmcdispatchstock-milk_quality_type_code, #tblbmcdispatchstock-bmc_silos_info_code', function() {
        updateFields();
    });

    function updateFields() {
        var milkTypeCode = $('#tblbmcdispatchstock-milk_type_code').val();
        var milkQualityTypeCode = $('#tblbmcdispatchstock-milk_quality_type_code').val();
        var bmcSiloInfoCode = $('#tblbmcdispatchstock-bmc_silos_info_code').val();
        var stockDetailArray = [];
        var check_key = bmcSiloInfoCode+ '_' + milkTypeCode + '_' + milkQualityTypeCode;
        if(setData(milkTypeCode) && setData(bmcSiloInfoCode) && setData(milkQualityTypeCode)) {
            var data = $('#stockdetail').val();
            if(setData(data) && isNaN(data)){
                stockDetailArray = jQuery.parseJSON(data);
                $('#tblbmcdispatchstock-opening_bal').val(0);
                $('#tblbmcdispatchstock-purchase_qty').val(0);
                if(stockDetailArray[String(check_key)] != '' && stockDetailArray[String(check_key)] != undefined){
                    $('#tblbmcdispatchstock-opening_bal').val(stockDetailArray[String(check_key)].previous_qty);
                    $('#tblbmcdispatchstock-purchase_qty').val(stockDetailArray[String(check_key)].purchase_qty);
                }
            }
        }
    }
    
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }   
    
    function setDatePurchaseInfo(){
        var bmcCode = $('#tblbmcdispatchstock-bmc_code').val();
        if(setData(bmcCode)){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['get-date-purchase-info']) . "',
                data: {'bmcCode':bmcCode},
                success: function(data) {   
                    if (data.result.status == 'success') {
                        $('#tblbmcdispatchstock-from_date').val(data.result.from_date).trigger('change');
                        $('#tblbmcdispatchstock-from_shift_code').val(data.result.from_shift).trigger('change').trigger('select2:select');
                        $('#tblbmcdispatchstock-to_date').val(data.result.to_date).trigger('change');
                        $('#tblbmcdispatchstock-to_shift_code').val(data.result.to_shift).trigger('change').trigger('select2:select');
                        $('#purchase-detial').html(data.tableHtml);
                        enableDisableDates(data.result.physical_stock_only);
                    } else {
                        resetFields();
                    }
                }
            });
        } else {
            resetFields();
	    }
    }
  
    function enableDisableDates(physicalStockOnly) {
        if (physicalStockOnly == 2) {
            $('.field-tblbmcdispatchstock-from_date, .field-tblbmcdispatchstock-to_date').removeClass('disabled no_pointer');
            $('.field-tblbmcdispatchstock-from_shift_code, .field-tblbmcdispatchstock-to_shift_code').removeClass('no_pointer_disabled');
        } else {
            $('.field-tblbmcdispatchstock-from_date, .field-tblbmcdispatchstock-to_date').addClass('disabled no_pointer');
            $('.field-tblbmcdispatchstock-from_shift_code, .field-tblbmcdispatchstock-to_shift_code').addClass('no_pointer_disabled');
        }
    }
        
    function resetFields() {
        $('#tblbmcdispatchstock-from_date').val('');
        $('#tblbmcdispatchstock-to_date').val('');
        $('#tblbmcdispatchstock-from_shift_code').val('').trigger('change');
        $('#tblbmcdispatchstock-to_shift_code').val('').trigger('change');
        $('#purchase-detial table tbody').html('');
        $('#purchase-detial table tbody').html('<tr><td colspan=\"6\" class=\"text-center\">No stock data available.</td></tr>');
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
<?php
$script = "
$(document).ready(function(){
    $(document).on('change', '#tblbmcdispatchstock-from_date, #tblbmcdispatchstock-to_date', function() {
        var from_date = $('#tblbmcdispatchstock-from_date').val();
        var to_date = $('#tblbmcdispatchstock-to_date').val();
        
        if (setData(from_date) && setData(to_date)) {
            // Split date strings and format them as yyyy-mm-dd
            var from_date_parts = from_date.split('-');
            var to_date_parts = to_date.split('-');
            var formatted_from_date = from_date_parts[2] + '-' + from_date_parts[1] + '-' + from_date_parts[0];
            var formatted_to_date = to_date_parts[2] + '-' + to_date_parts[1] + '-' + to_date_parts[0];
            
            var fromDateObj = new Date(formatted_from_date);
            var toDateObj = new Date(formatted_to_date);

            if (isNaN(fromDateObj) || isNaN(toDateObj) || toDateObj < fromDateObj) {
                var errorMessage = 'must not be less than from date.';
                var errorElement = '<div class=\"error-message error_message\">' + errorMessage + '</div>';
                $('.field-tblbmcdispatchstock-to_date .error-message').remove();
                $('.field-tblbmcdispatchstock-to_date').append(errorElement);
            } else {
                $('.field-tblbmcdispatchstock-to_date .error-message').remove();
            }
        } else {
            $('.field-tblbmcdispatchstock-to_date .error-message').remove();
        }
    });
});";
$this->registerJs($script, View::POS_END, 'to-date-from-date');
?>

