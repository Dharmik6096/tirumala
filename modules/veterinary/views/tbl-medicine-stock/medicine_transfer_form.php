<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
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
            <h4 class="theme-box-heading">Medicine Stock Transfer Details</h4>
        </div>
        <div class="col-md-12">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmedicinestock-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmedicinestock-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmedicinestock-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmedicinestock-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, '', $readonly); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->UserList($model, $form, 'tblmedicinestock-union_code,tblmedicinestock-plant_code,tblmedicinestock-mcc_plant_code,tblmedicinestock-bmc_code,tblmedicinestock-dcs_code', 'from_user_code', $model->getAttributeLabel('From User'), FALSE, FALSE, '/veterinary/tbl-medicine-stock/user-list'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->UserList($model, $form, 'tblmedicinestock-union_code', 'to_user_code', $model->getAttributeLabel('to User'), FALSE, FALSE, '/veterinary/tbl-medicine-stock/all-user-list'); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= $form->field($model, 'medicine_wise', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(['checked' => true]); ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Medicine Stock Txn Transfer Details</h4>
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
                                                                    $(".create_fields").removeClass("disabled");
                                                                    $(".DisableAferAdd").addClass("disabledDiv");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $("#tblinventorytransfer-inventory_transfer_code").val(data.pk_code);
                                                                    reloadGrid(data.pk_code);
                                                                    $(".transporter").hide();
                                                                    $("#inventory-transfer-form .reset_field input").val("");
                                                                    $("#inventory-transfer-form .reset_field select").val("");
                                                                    $("#inventory-transfer-form .reset_field textarea").val("");
                                                                    // $("#tblinventorytransfertxn-product_code").val("");
                                                                    // $("#tblinventorytransfertxn-product_code").trigger("select2:select");
                                                                    // $("#tblinventorytransfertxn-product_code").trigger("change");
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
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                    ],
                    'options' => [
                        'class' => 'btn btn-default btn-raised',
                        'type' => 'submit'
                    ],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
            </div>

        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>