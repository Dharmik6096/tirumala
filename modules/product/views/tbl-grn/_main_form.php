<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$list = array('0' => 'No', '1' => 'Yes');
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$withoutDispatch = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'without_dispatch_grn', 'PORTAL');
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'grn-form'],
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
            <h4 class="theme-box-heading"><?= Yii::t('app', 'GRN') ?></h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= $form->field($model, 'grn_no')->textInput(['readonly' => TRUE]) ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'grn_date', '', false, false, false, true); ?>
        </div>
        <?php if ($batchNoWiseInventory == 1 && $withoutDispatch == 1) { ?>
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblgrn-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
            </div>
        <?php } else { ?>
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->depend_dropdown('vendor', $model, $form, 'tblgrn-union_code', 'form-group', $model->getAttributeLabel('vendor_master_code'), 'vendor_master_code'); ?>
            </div>
        <?php } ?>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblgrn-union_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'invoice_date', '', false, false, false, true); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= $form->field($model, 'invoice_no')->textInput() ?>
        </div>
        <div class="col-sm-4 create_fields">
            <?= $form->field($model, 'remarks')->textInput() ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Product Details</h4>
        </div>
        <?= $form->field($model, 'grn_code')->hiddenInput()->label(FALSE) ?>
        <div class="col-sm-2 reset_field">
            <?php Yii::$app->dropdown->depend_dropdown('product', $txModel, $form, 'tblgrn-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
        </div>
        <?php if ($batchNoWiseInventory == 1 && $withoutDispatch == 1) { ?>
            <div class="col-sm-2 reset_field number-validate">
                <?= $form->field($txModel, 'sap_batch_no')->textInput() ?>
            </div>
        <?php } ?>
        <div class=" col-sm-2 reset_field unit disabledDiv">
            <?= Yii::$app->dropdown->dropdown('unit_code', $txModel, $form, 'form-group col-sm-2', $txModel->getAttributeLabel('unit_code'), FALSE, 'unit_code'); ?>    
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'rate')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field qty-validate">
            <?= $form->field($txModel, 'received_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field qty-validate">
            <?= $form->field($txModel, 'rejected_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'basic_amount')->textInput(['readonly' => TRUE]) ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'tax')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'gross_amount')->textInput(['readonly' => TRUE]) ?>
        </div>

        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Add'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['create']),
                        'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
//                                                                    $(".create_fields input").prop("disabled", true);
                                                                      $(".create_fields").removeClass("disabled");
//                                                                    $(".create_fields select").prop("disabled", true);                                                                  
                                                                       $(".DisableAferAdd").addClass("disabledDiv");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $("#tblgrn-grn_code").val(data.pk_code);
                                                                    reloadGrid(data.pk_code);
                                                                    $(".transporter").hide();
                                                                    $("#grn-form .reset_field input").val("");
                                                                    $("#grn-form .reset_field select").val("");
                                                                    $("#grn-form .reset_field textarea").val("");
                                                                    $("#tblgrntxn-product_code").val("");
                                                                    $("#tblgrntxn-product_code").trigger("select2:select");
                                                                    $("#tblgrntxn-product_code").trigger("change");
                                                                    $("#tblgrntxn-unit_code").val("");
                                                                    $("#tblgrntxn-unit_code").trigger("select2:select");
                                                                    $("#tblgrntxn-unit_code").trigger("change");
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                  
                                                                
                                                                setTimeout(function(){
                                                                   $("#tblbmccollection-customer_code").focus();},100);
                                                                    });
                                                                }else{
                                                                
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
//                                                                    $("#tblgrn-grn_code").val(pk_code);
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                    ],
                    'options' => ['class' => 'btn-login btn btn-default btn-raised',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
            </div>

        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

