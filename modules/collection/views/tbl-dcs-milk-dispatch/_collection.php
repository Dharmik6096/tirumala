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
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'milk-dispatch-form'],
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
            <h4 class="theme-box-heading">Milk Dispatch Collection</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcsmilkdispatch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcsmilkdispatch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcsmilkdispatch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields">
            <?= Yii::$app->controls->date($model, $form, 'date_time_of_dispatch', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-1 shift rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
        </div>
        <?php if (Yii::$app->session->get('eiplCode') == 'PRABHAT') { ?>
            <div class="col-sm-1 create_fields">
                <?= Yii::$app->dropdown->dropdownStatic('antibiotic', $model, $form, '', 'Antibiotic', $readonly, 'antibiotic') ?> 
            </div>
        <?php } ?>
        <div class="col-sm-1 padding_top_20 Button disabled mb25 ml15">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Dispatch') ?></button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Dispatch Collection Details</h4>
        </div>
        <?= $form->field($model, 'dcs_code')->hiddenInput()->label(FALSE) ?>
        <div class="col-sm-1 rtpl_validate create_fields reset_field">
            <?= $form->field($model, 'dcs')->textInput()->label(Yii::t('app', 'Code')) ?>
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($model, 'name')->textInput(['disabled' => TRUE])->label(Yii::t('app', 'Name')) ?>
        </div>
        <div class="col-sm-1 rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $txModel, $form, '', 'Milk Type', $readonly); ?>
        </div>

        <div class="col-sm-1 rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $txModel, $form, '', $txModel->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
        </div>
        <!-- <div class="clearfix"></div> -->
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'dispatch_qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'avg_fat')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'avg_snf')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field rtpl_validate ">
            <?= $form->field($txModel, 'avg_clr')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'rtpl')->textInput(['readOnly' => true]) ?>
            <?= $form->field($txModel, 'purchase_rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'total_amount')->textInput(['readOnly' => true]) ?>
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
                                                                     
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $(".transporter").hide();
                                                                    $("#milk-dispatch-form .reset_field input").val("");
                                                                    $("#milk-dispatch-form .reset_field select").val("");
                                                                    $("#milk-dispatch-form .reset_field textarea").val("");

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

