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
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcdispatchstock-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcdispatchstock-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcdispatchstock-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift_code', true, $readonly, 'from_shift_code'); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift create_fields">
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
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
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
        <div class="col-sm-1"> 
            <?= Yii::$app->dropdown->dropdown('qty_diff_type', $model, $form, '', true, false, 'qty_diff_type_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'opening_bal')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'purchase_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'qty_diff')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($model, 'balance_qty')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
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
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $("#milk-collection-from .reset_field input").val("");
                                                                    $("#milk-collection-from .reset_field select").val("");
                                                                    $("#milk-collection-from .reset_field textarea").val("");

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

<?php ActiveForm::end(); ?>



