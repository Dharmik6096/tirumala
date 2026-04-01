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
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'inventory-transfer-form'],
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
            <h4 class="theme-box-heading">Inventory Transfer Details</h4>
        </div>
        <div class="col-md-10">
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
            </div>
            <div class="col-sm-2 create_fields">
                <?= $form->field($model, 'inventory_transfer_no')->textInput() ?>
            </div>

            <?php if ($batchNoWiseInventory == 1) { ?>
                <div class="col-sm-2 create_fields">
                    <?php
                    $minDate = date('Y-m-d', strtotime("-4 days"));
                    ?>
                    <?= Yii::$app->controls->date($model, $form, 'inventory_transfer_date', '', date('Y-m-d'), $minDate, false, true); ?>
                </div>
                <div class="col-sm-2 create_fields">
                    <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', TRUE, date('Y-m-d'), TRUE, true); ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-2 create_fields">
                    <?= Yii::$app->controls->date($model, $form, 'inventory_transfer_date', '', false, false, false, true); ?>
                </div>
            <?php } ?> 
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->dropdownStatic('org_type', $model, $form, 'form-group', $model->getAttributeLabel('from_type'), false, 'from_type', false); ?>
            </div>
            <div class="col-sm-2 create_fields" id="from_mcc">
                <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblinventorytransfer-union_code', 'from_mcc_plant_code', $model->getAttributeLabel('from_mcc_plant_code')); ?>
            </div>
            <div class="col-sm-2" id="from_bmc">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinventorytransfer-from_mcc_plant_code', 'from_bmc_code', $model->getAttributeLabel('from_bmc_code')); ?>
            </div>
            <div class="col-sm-2" id="from_dcs">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinventorytransfer-from_bmc_code', 'from_dcs_code', $model->getAttributeLabel('from_dcs_code'), FALSE, '', FALSE, TRUE); ?>
            </div>
            <?= Html::activeHiddenInput($model, 'from_code', ['id' => 'f_code']) ?>
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->dropdownStatic('org_type', $model, $form, 'form-group', $model->getAttributeLabel('to_type'), false, 'to_type', false); ?>
            </div>
            <div class="col-sm-2 create_fields" id="to_mcc">
                <?php echo Html::hiddenInput('rls', 'false', ['id' => 'rls']); ?>
                <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblinventorytransfer-union_code,rls', 'to_mcc_plant_code', $model->getAttributeLabel('to_mcc_plant_code')); ?>
            </div>
            <div class="col-sm-2 create_fields" id="to_bmc">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinventorytransfer-to_mcc_plant_code,rls', 'to_bmc_code', $model->getAttributeLabel('to_bmc_code')); ?>
            </div>
            <div class="col-sm-2 create_fields" id="to_dcs">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinventorytransfer-to_bmc_code,rls', 'to_dcs_code', $model->getAttributeLabel('to_dcs_code'), FALSE, '', FALSE, TRUE); ?>
            </div>
            <div class="col-sm-2 create_fields" id="to_dcs_sap_vendor">
                <?= $form->field($txModel, 'sap_vendor_code')->textInput(['readonly' => TRUE])->label(Yii::t('app', 'DCS ') . 'Vendor Code') ?>
            </div>
            <?= Html::activeHiddenInput($model, 'to_code', ['id' => 't_code']) ?>
            <div class="col-sm-4 create_fields">
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Inventory Transfer Txn Details</h4>
        </div>
        <?= $form->field($model, 'inventory_transfer_code')->hiddenInput()->label(FALSE) ?>
        <div class="col-sm-3"> 
            <?= Html::hiddenInput('x_col3', '2', ['id' => 'x_col3']); ?>
            <?php Yii::$app->dropdown->depend_dropdown('product', $txModel, $form, 'tblinventorytransfer-union_code,x_col3', 'form-group col-sm-2 padding-right-5 padding-left-0', $txModel->getAttributeLabel('product_code'), 'product_code'); ?>
        </div>
        <?php if ($batchNoWiseInventory == 1) { ?>
            <div class="col-sm-2 create_fields">
                <?= Yii::$app->dropdown->productBatch($txModel, $form, 'tblinventorytransfer-from_type,f_code,tblinventorytransfertxn-product_code', 'sap_batch_no', $txModel->getAttributeLabel('sap_batch_no'), FALSE); ?> 
            </div>
        <?php } ?>
        <div class=" col-sm-2 reset_field unit disabledDiv">
            <?= Yii::$app->dropdown->dropdown('unit_code', $txModel, $form, 'form-group col-sm-2', $txModel->getAttributeLabel('unit_code'), FALSE, 'unit_code'); ?>    
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'available_stock')->textInput(['readonly' => TRUE])->label(Yii::t('app', 'Available Stock')) ?>
        </div>
        <div class="col-sm-2 create_fields reset_field number-validate">
            <?= $form->field($txModel, 'qty')->textInput()->label(Yii::t('app', 'Quantity')) ?>
        </div>


        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'SAVE'),
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
                                                                    $("#tblinventorytransfer-inventory_transfer_code").val(data.pk_code);
                                                                    reloadGrid(data.pk_code);
                                                                    $(".transporter").hide();
                                                                  
                                                                    $("#inventory-transfer-form .reset_field input").val("");
                                                                    $("#inventory-transfer-form .reset_field select").val("");
                                                                    $("#inventory-transfer-form .reset_field textarea").val("");
                                                                    $("#tblinventorytransfertxn-product_code").val("");
                                                                    $("#tblinventorytransfertxn-product_code").trigger("select2:select");
                                                                    $("#tblinventorytransfertxn-product_code").trigger("change");
                                                                    $("#tblinventorytransfertxn-unit_code").val("");
                                                                    $("#tblinventorytransfertxn-unit_code").trigger("select2:select");
                                                                    $("#tblinventorytransfertxn-unit_code").trigger("change");
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
                                                                        if (key === "actual_stock") {
                                                                            $("#tblinventorytransfertxn-available_stock").val(val);
                                                                        } else {
                                                                            $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        }
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
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
            </div>

        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

