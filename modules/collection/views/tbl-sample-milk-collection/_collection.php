<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'sample-milk-collection-from'],
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
            <h4 class="theme-box-heading">Sample Milk Collection</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblsamplemilkcollection-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblsamplemilkcollection-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblsamplemilkcollection-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-1 rtpl_validate create_fields">
            <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-1 shift rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
        </div>
        <div class="col-sm-1 Button disabled mb25 ml15 padding_top_20">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Collection') ?></button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Collection Details</h4>
        </div>
        <div class="col-sm-2  create_fields">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblsamplemilkcollection-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
        </div>
        <div class="col-sm-1  rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', $model->getAttributeLabel('milk_type_code'), $readonly, 'milk_type_code'); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields milk_quality_type_div">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
        </div>
        <div class="col-sm-1  reset_field number-validate">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field rtpl_validate number-validate">
            <?= $form->field($model, 'fat')->textInput() ?>
        </div>
        <div class="col-sm-1  reset_field rtpl_validate number-validate">
            <?= $form->field($model, 'snf')->textInput() ?>
        </div>
        <div class="col-sm-1  reset_field  number-validate">
            <?= $form->field($model, 'clr')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'rtpl')->textInput(['readOnly' => true]) ?>
            <?= $form->field($model, 'purchase_rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('source_of_milk', $model, $form, 'form-group', $model->getAttributeLabel('source_of_milk'), false, 'source_of_milk', false); ?>
        </div>
        <!--<div class="clearfix"></div>-->
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
                                                                    milkQualityType();
                                                                    $("#sample-milk-collection-from .reset_field input").val("");
                                                                    $("#sample-milk-collection-from .reset_field select").val("").trigger("change");
                                                                    $("#sample-milk-collection-from .reset_field textarea").val("");

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
                                                                        if(key != "tblsamplemilkcollection-date_time_of_collection"){
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
                    'options' => ['class' => 'btn btn-default btn-raised btn-login',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?> 
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
