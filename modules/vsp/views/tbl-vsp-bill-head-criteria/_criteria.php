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
//$url = $type == 'create' ? 'create' : 'update';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'bill-head-criteria-from'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="modal modal-default fade" id="apply-formula" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Set Value For Formula'); ?></h4>
            </div>

            <div class="modal-body">         
                <div class="row">
                    <div class="col-sm-12 mb15" id="subtitle">

                        <?php
                        //$key = key($formulaArray);
                        //echo Html::radioList('formula', '', $formulaArray, ['class' => 'radio radio-list', 'itemOptions' => ['class' => ''],]);
                        ?>
                    </div>
                    <div id="div_formula" class="col-sm-10 mb15 form-group field ">
                        <?= Html::input('text', 'value', '', ['class' => 'form-control', 'id' => 'replace_value']) ?>

                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Ok'), ['class' => 'btn btn-primary', 'id' => 'formula-submit']); ?>

                <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>

        </div>
    </div>
</div>


<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bill Head Criteria</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2  <?= $disable ?> ">
            <?= $form->field($model, 'criteria_name')->textInput()->label('Name') ?>
        </div>       
        <div class="col-sm-2 <?= $disable ?>">
            <?= Yii::$app->dropdown->dropdown('slab_bill_head', $model, $form, '', TRUE, FALSE, 'bill_head_code'); ?>
        </div>
        <div class="col-sm-2  <?= $disable ?> " id="product-wise-slab">
            <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblvspbillheadcriteria-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product', 'criteria_code'); ?>
        </div>
        <div class="col-sm-2 <?= $disable ?>">
            <?= Yii::$app->dropdown->depend_dropdown('general_formula_code', $model, $form, 'tblvspbillheadcriteria-union_code', '', $model->getAttributeLabel('general_formula_code')); ?>
        </div>
        <div class="col-sm-1 Button disabled mb25 ml15 padding_top_20">
            <button type="button" class="add-criteria btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Criteria') ?></button>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Criteria Slab</h4>
        </div>
        <?= $form->field($model, 'vsp_criteria_code')->hiddenInput()->label(false) ?>

        <div class="col-sm-2 number-validate">
            <?= $form->field($txModel, 'from_val')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="col-sm-2 reset_field number-validate">
            <?= $form->field($txModel, 'to_val')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->depend_dropdown('general_formula_code', $txModel, $form, 'tblvspbillheadcriteria-union_code', '', $model->getAttributeLabel('general_formula_code'), 'general_formula_code', TRUE); ?>
        </div>       
        <div class="col-sm-2  reset_field" id="general_formula">
            <?= $form->field($txModel, 'formula_with_val')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="Button mb25 ml15 padding_top_20">
            <button type="button" class="add-formula btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Val') ?></button>
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
//                                                                    $(".create_fields").removeClass("disabled");
//                                                                    $(".create_fields select").prop("disabled", true);                                                                  
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $("#bill-head-criteria-from .reset_field input").val("");
                                                                    $("#bill-head-criteria-from .reset_field select").val("");
                                                                    $("#bill-head-criteria-from .reset_field textarea").val("");

                                                                    $(".panel-body").scrollTop(0);
                                                                   bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                    $("#tblvspbillheadcriteria-vsp_criteria_code").val(data.pk_code);
                                                                     reloadGrid();
                                                                    $("#tblvspbillheadcriteriaslabs-from_val").focus();},100);
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
                <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
