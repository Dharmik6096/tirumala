<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
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
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Physical Stock Punching</h4>
        </div>
        <div class="col-sm-2 filldata">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 filldata">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcdispatchstock-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 filldata">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcdispatchstock-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2 filldata">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcdispatchstock-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-2 filldata">
            <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift filldata">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift_code', true, $readonly, 'from_shift_code'); ?>
        </div>
        <div class="col-sm-2 filldata">
            <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift filldata">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift_code', true, $readonly, 'to_shift_code'); ?>
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
            <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $model, $form, 'tblbmcdispatchstock-bmc_code,tblbmcdispatchstock-module_name', 'form-group col-sm-4', $model->getAttributeLabel('bmc_silos_info_code'), '', false, '', '', FALSE, '', TRUE); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'fat')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'snf')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'water')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field"> 
            <?= Yii::$app->dropdown->dropdown('qty_diff_type', $model, $form, '', true, false, 'qty_diff_type_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'opening_bal')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'purchase_qty')->textInput() ?>
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
                'label' => Yii::t('app', 'Add'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                console.log(data.data);
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
    
    $('#tblbmcdispatchstock-opening_bal').on('change', function(){
        calculateQtyDiff();
    });
    $('#tblbmcdispatchstock-purchase_qty').on('change', function(){
        calculateQtyDiff();
    });
    $('#tblbmcdispatchstock-balance_qty').on('change', function(){
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
//    $(document).on('click','#submitBtn', function() {
//    alert('hi');
//        var union_code = $('#tblbmcdispatchstock-union_code').val();
//        var plant_code = $('#tblbmcdispatchstock-plant_code').val();
//        var mcc_code = $('#tblbmcdispatchstock-mcc_plant_code').val();
//        var bmc_code = $('#tblbmcdispatchstock-bmc_code').val();
//        var to_date = $('#tblbmcdispatchstock-to_date').val();
//        var to_shift = $('#tblbmcdispatchstock-to_shift_code').val();
//       if(to_date != '' && to_shift !='' && bmc_code != '' && union_code !=''&& plant_code !=''&& mcc_code !=''){
//            $('#transactions-detial').html('');           
//            BindData(union_code,plant_code,mcc_code,bmc_code,to_date,to_shift);            
//        }      
//    });
    
//    function BindData(union_code,plant_code,mcc_code,bmc_code,to_date,to_shift){
//        $.ajax({
//                type: 'get',
//                url: '" . Url::to(['transaction-detail']) . "',
//                data: {'union_code' : union_code,'plant_code':plant_code,'mcc_code' : mcc_plant_code,'bmc_code':bmc_code,'to_date' : to_date,'to_shift' : to_shift_code},             
//                success: function(data) {
//                  $('#transactions-detial').html(data);
//                },
//                error: function(data) {  
//                }
//            });     
//    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

