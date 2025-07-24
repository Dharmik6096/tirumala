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
$allowCanSelection = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'allow_can_selection', 'PORTAL') == 1 ? TRUE : FALSE;
$allowRouteSelection = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'allow_route_selection', 'PORTAL') == 1 ? TRUE : FALSE;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'bmc-coll-form'],
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
            <h4 class="theme-box-heading">BMC Collection</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmccollection-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmccollection-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmccollection-mcc_plant_code', 'own_bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->poured_bmc($model, $form, 'tblbmccollection-own_bmc_code', 'bmc_code', Yii::t('app', 'Poured BMC'), FALSE); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields">
            <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
        </div>
        <div class="col-sm-1 padding_top_20 Button disabled mb25 ml15">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Collection') ?></button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">BMC Collection Details</h4>
        </div>
        <div class="col-sm-12 container">
            <div class="col-sm-10">
                <div class="QltyParamDiv inline_block">
                    <?php if ($allowRouteSelection) { ?>
                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->all_routes($model, $form, 'tblbmccollection-plant_code,tblbmccollection-mcc_plant_code,tblbmccollection-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
                        </div>
                    <?php } ?>
                    <div class="col-sm-2 create_fields">
                        <?php echo Html::hiddenInput('is_clr_input', 0, ['id' => 'is_clr_input']); ?>
                        <?= Yii::$app->dropdown->customer_type($model, $form, 'tblbmccollection-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
                    </div>
                    <div class="col-sm-2 rtpl_validate create_fields reset_field">
                        <?= Yii::$app->dropdown->activate_customer_code($model, $form, 'tblbmccollection-customer_type,tblbmccollection-union_code,tblbmccollection-bmc_code,tblbmccollection-date_time_of_collection', 'customer_code', $model->getAttributeLabel('customer_code'), FALSE, false, false); ?>
                    </div>
                    <div class="col-sm-2 rtpl_validate create_fields">
                        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
                    </div>
                    <div class="col-sm-2 create_fields">
                        <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmccollection-module_name']); ?>
                        <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $model, $form, 'tblbmccollection-bmc_code,tblbmccollection-module_name', 'form-group col-sm-4', $model->getAttributeLabel('bmc_silos_info_code'), '', false, '', '', FALSE, '', TRUE, TRUE, FALSE, FALSE); ?>
                    </div>
                    <div class="col-sm-2 rtpl_validate create_fields">
                        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
                    </div>
                    <div class="col-sm-1 reset_field number-validate">
                        <?= $form->field($model, 'qty')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field number-validate">
                        <?= $form->field($model, 'fat')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field number-validate snf_calculate">
                        <?= $form->field($model, 'snf')->textInput() ?>
                    </div>
                    <div class="col-sm-1 reset_field number-validate rtpl_validate clr_calculate ">
                        <?= $form->field($model, 'clr')->textInput(['readOnly' => true]) ?>
                    </div>
                    <?php if ($allowCanSelection) { ?>
                        <div class="col-sm-1 create_fields">
                            <?= $form->field($model, 'can_no')->textInput() ?>
                        </div>
                    <?php } ?>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($model, 'scheme_rate')->textInput(['readOnly' => true]) ?>
                        <?= $form->field($model, 'scheme_rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
                    </div>
                    <div class="col-sm-1 reset_field">
                        <?= $form->field($model, 'actual_rate')->textInput(['readOnly' => true]) ?>
                    </div>
                    <div class="col-sm-1 reset_field hide_help_block">
                        <?= $form->field($model, 'rtpl')->textInput(['readOnly' => true]) ?>
                        <?= $form->field($model, 'rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
                    </div>
                    <div class="col-sm-2 reset_field">
                        <?php // Html::activeHiddenInput($model, 'milk_collection_code', ['value' => $model->milk_collection_code])  ?>
                        <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
                    </div>
                    <?php if (Yii::$app->session->get('eiplCode') == 'PRABHAT') { ?>
                        <div class="col-sm-2 create_fields">
                            <?= Yii::$app->dropdown->dropdownStatic('antibiotic', $model, $form, '', 'Antibiotic', $readonly, 'antibiotic') ?> 
                        </div>
                    <?php } ?>
                    <div class="col-sm-2 reset_field ">
                        <?= Yii::$app->dropdown->dropdownStatic('collection_type', $model, $form, 'form-group', $model->getAttributeLabel('collection_type'), false, 'collection_type', false); ?>
                    </div>
                </div>
                <div class="transporter">
                    <div class="col-sm-2 reset_field">
                        <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', true, true, 'transporter_code'); ?>
                    </div>
                    <div class='col-sm-2 reset_field'>
                        <?= Yii::$app->dropdown->vehicletransporter($model, $form, 'tblbmccollection-transporter_code', 'vehicle_code', 'Vehicle'); ?>
                    </div>
                    <div class="col-sm-2 reset_field">
                        <?=
                        $form->field($model, 'route_arrival_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                            'mask' => '99:99',])
                        ?> 
                    </div>
                </div>
            </div>
            <div class="col-sm-2 reset_field">
                <?= $form->field($model, 'remarks')->textarea() ?>
            </div>
        </div>   
        <div class="col-sm-2 padding_top_20 shortcut-main ml15" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
                                                                    $("#bmc-coll-form .reset_field input").val("");
                                                                    $("#bmc-coll-form .reset_field select").val("").trigger("change");
                                                                    $("#bmc-coll-form .reset_field textarea").val("");

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