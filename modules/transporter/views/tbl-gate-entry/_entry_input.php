<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Html;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'gate-entry-form'],
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
            <h4 class="theme-box-heading">Gate Entry</h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblgateentry-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblgateentry-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2 create_fields <?= $disable ?>">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblgateentry-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
        </div>
        <div class="col-sm-1 padding_top_20 Button disabled mb25 ml15">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add') ?></button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Gate Entry Details</h4>
        </div>
        <div class="col-sm-10">
            <div class="row">
                <div class="QltyParamDiv">
                    <div class="col-sm-2 reset_field create_fields <?= $disable ?>">
                        <?= Html::activeHiddenInput($model, 'gate_entry_code'); ?>
                        <?= Yii::$app->dropdown->all_routes($model, $form, 'tblgateentry-plant_code,tblgateentry-mcc_plant_code,tblgateentry-bmc_code', 'route_code', $model->getAttributeLabel('route_code')); ?>
                    </div>
                    <div class="col-sm-2 reset_field create_fields <?= $disable ?>">
                        <?= $form->field($model, 'vehicle_code')->textInput() ?>
                    </div>
                    <div class="col-sm-2 reset_field">
                        <?=
                        $form->field($model, 'actual_arrival_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                            'mask' => '99:99',])
                        ?> 
                    </div>
                    <div class="col-sm-1 reset_field disabled">
                        <?=
                        $form->field($model, 'define_arrival_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                            'mask' => '99:99',])
                        ?> 
                    </div>
                    <div class="col-sm-1 reset_field number-validate disabled">
                        <?= $form->field($model, 'grace_time')->textInput() ?>
                    </div>
                    <div class="col-sm-2 reset_field number-validate">
                        <?= $form->field($model, 'no_of_filled_can')->textInput() ?>
                    </div>
                    <div class="col-sm-2 reset_field number-validate">
                        <?= $form->field($model, 'no_of_empty_can')->textInput() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
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
                                                                    $("#tblgateentry-route_code").removeAttr("disabled");
                                                                    $("#tblgateentry-vehicle_code").removeAttr("disabled");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $("#gate-entry-form .reset_field input").val("");
                                                                    $("#gate-entry-form .reset_field select").val("");
                                                                    $("#gate-entry-form .reset_field textarea").val("");
                                                                    $("#tblgateentry-route_code").change();
                                                                    $(".DisableAferAdd").addClass("disabledDiv");                                                                  
                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblgateentry-route_code").focus();},100);
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
                <?= Yii::$app->controls->custombutton('Cancel', 'create'); ?> 
            </div>

        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>