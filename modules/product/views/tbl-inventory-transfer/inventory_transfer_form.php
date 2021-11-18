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
        <div class="col-sm-1 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-1 create_fields">
            <?= $form->field($model, 'inventory_transfer_no')->textInput()->label(Yii::t('app', 'inventory transfer no')) ?>
        </div>
        <div class="col-sm-1 rtpl_validate create_fields">
            <?= Yii::$app->controls->date($model, $form, 'inventory_transfer_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('org_type', $model, $form, 'form-group', $model->getAttributeLabel('from_type'), false, 'from_type', false); ?>
        </div>
        <div class="col-sm-1" id="from_mcc">
            <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblinventorytransfer-union_code', 'from_mcc_plant_code', $model->getAttributeLabel('mcc_code')); ?>
        </div>
        <div class="col-sm-1" id="from_bmc">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinventorytransfer-from_mcc_plant_code', 'from_bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <div class="col-sm-1" id="from_dcs">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinventorytransfer-from_bmc_code', 'from_dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', FALSE, TRUE); ?>
        </div>
        <?= Html::activeHiddenInput($model, 'from_code', ['id' => 'f_code']) ?>
        <div class="col-sm-1 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('org_type', $model, $form, 'form-group', $model->getAttributeLabel('to_type'), false, 'to_type', false); ?>
        </div>
        <div class="col-sm-1" id="to_mcc">
            <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblinventorytransfer-union_code', 'to_mcc_plant_code', $model->getAttributeLabel('mcc_code')); ?>
        </div>
        <div class="col-sm-1" id="to_bmc">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinventorytransfer-to_mcc_plant_code', 'to_bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <div class="col-sm-1" id="to_dcs">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinventorytransfer-to_bmc_code', 'to_dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', FALSE, TRUE); ?>
        </div>
        <?= Html::activeHiddenInput($model, 'to_code', ['id' => 't_code']) ?>
        <div class="col-sm-2 reset_field">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Inventory Transfer Txn Details</h4>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('product', $txModel, $form, 'tblinventorytransfer-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product', 'product_code'); ?>
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'available_stock')->textInput()->label(Yii::t('app', 'Available Stock')) ?>
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'unit_code')->textInput()->label(Yii::t('app', 'Unit Code')) ?>
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'qty')->textInput()->label(Yii::t('app', 'Quantity')) ?>
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
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $(".transporter").hide();
                 
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblinventorytransfer-from_type").focus();},100);
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

