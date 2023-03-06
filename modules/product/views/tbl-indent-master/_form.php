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
            'options' => ['id' => 'indent-master-from'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom hide_help_block">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Indent Master</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblindentmaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblindentmaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblindentmaster-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'indent_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>

        <div class="col-sm-1 Button disabled mb25 ml15 padding_top_20">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Indent') ?></button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Indent Master Details</h4>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblindentmaster-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
        </div>
        <div class="col-sm-2 reset_field no_padding_input">
            <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblindentmaster-dcs_code', '', $model->getAttributeLabel('member_code')); ?>
        </div>
        <div class="col-sm-2 reset_field no_padding_input">
            <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblindentmaster-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
        </div>
        <div class="col-sm-1 reset_field qty-validate">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="clearfix"></div>
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
//                                                                    $(".create_fields").removeClass("disabled");
//                                                                    $(".create_fields select").prop("disabled", true);                                                                  
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                     
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $("#indent-master-from .reset_field input").val("");
                                                                    $("#indent-master-from .reset_field select").val("");
                                                                    $("#indent-master-from .reset_field textarea").val("");
                                                                    $("#tblindentmaster-dcs_code").trigger("change");
                                                                    $("#tblindentmaster-dcs_code").trigger("select2:select");
                                                                    $("#tblindentmaster-member_code").trigger("change");
                                                                    $("#blindentmaster-member_code").trigger("select2:select");
                                                                    $("#tblindentmaster-product_code").trigger("change");
                                                                    $("#tblindentmaster-product_code").trigger("select2:select");
                                                                    $(".panel-body").scrollTop(0);
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblbmccollection-dcs").focus();},100);
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
                                                                        if(key != "tblmilkcollection-date_time_of_collection"){
                                                                        var parent_div = $("#"+key).parent("div");
                                                                        parent_div.find(".help-block").remove();
                                                                        $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                                        $("#"+key).closest(".form-group").addClass("has-error");   
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
