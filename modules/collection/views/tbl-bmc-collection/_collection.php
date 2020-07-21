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
            'options' => ['id' => 'bmc-coll-form'],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="DisableAferAdd">
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
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmccollection-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields">
            <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, $readonly, true); ?>
        </div>
        <div class="col-sm-2 shift rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, $readonly, 'shift_code'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="Button disabled mb25 ml15">
            <button type="button" class="add-collection btn btn-default apply-shortcut ml15 "><?= Yii::t('app', 'Add Collection') ?></button>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="QltyParamDiv">
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->customer_type($model, $form, 'tblbmccollection-bmc_code', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields reset_field">
            <?= $form->field($model, 'customer_code')->textInput() ?>
        </div>
        <div class="col-sm-2 create_fields reset_field">
            <?= $form->field($model, 'customer_name')->textInput(['disabled' => TRUE])->label(Yii::t('app', 'Name')) ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?php echo Html::hiddenInput('module_name', 'BMC', ['id' => 'tblbmccollection-module_name']); ?>
            <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $model, $form, 'tblbmccollection-bmc_code,tblbmccollection-module_name', 'form-group col-sm-4', $model->getAttributeLabel('bmc_silos_info_code'), ''); ?>
        </div>
        <div class="col-sm-2 rtpl_validate create_fields">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', $model->getAttributeLabel('milk_quality_type_code'), $readonly, 'milk_quality_type_code'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'fat')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'snf')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field rtpl_validate ">
            <?= $form->field($model, 'clr')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($model, 'rtpl')->textInput(['readOnly' => true]) ?>
            <?= $form->field($model, 'rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?php // Html::activeHiddenInput($model, 'milk_collection_code', ['value' => $model->milk_collection_code]) ?>
            <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-3 reset_field">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>
        <div class="col-sm-3 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('collection_type', $model, $form, 'form-group', $model->getAttributeLabel('collection_type'), false, 'collection_type', false); ?>
        </div>
        <div class="transporter">
            <div class="col-sm-3 reset_field">
                <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', true, true, 'transporter_code'); ?>
            </div>
            <div class='col-sm-3 reset_field'>
                <?= Yii::$app->dropdown->vehicletransporter($model, $form, 'tblbmccollection-transporter_code', 'vehicle_code', 'Vehicle'); ?>
            </div>
            <div class="col-sm-2 reset_field">
                <?=
                $form->field($model, 'route_arrival_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                    'mask' => '99:99',])
                ?> 
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
                                                                    $("#bmc-coll-form .reset_field select").val("");
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