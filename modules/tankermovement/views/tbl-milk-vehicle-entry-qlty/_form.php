<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>
<?php $url = Url::to(['/tankermovement/tbl-milk-vehicle-entry-qlty/qlty-submit', 'TblShipmentSummary' => ['trip_code' => $model->trip_code]]); ?>
<?php
$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-group popup-form',
                'id' => 'milk-vehicle-entry-qlty-form',
            ],
        ]);
?>
<div class="row " style="margin-top: 23px;">
    <div class="col-sm-12 col-md-12">
        <?php echo $form->errorSummary($model); ?>
    </div>
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix custhead">
        <h4 class="theme-box-heading " style="padding: 5px;">Compartment Wise Tanker Milk Quality</h4>
        <div class="table-responsive padding_left_10">
            <div class="col-sm-3">
                <?php echo Yii::t('app', 'Compartment No') ?>
                <?php echo Html::hiddenInput('status', 'pending', ['id' => 'tblmilkvehicleentryqlty-status']); ?>
                <?php echo Html::hiddenInput('trip_code', '', ['id' => 'hidden-trip-code']); ?>
                <?php echo Html::hiddenInput('union_code', '', ['id' => 'hidden-union-code']); ?>
                <?= Yii::$app->dropdown->depend_dropdown('chamber_no', $model, $form, 'tblmilkvehicleentryqltysearch-trip_code,tblmilkvehicleentryqlty-status', 'form-group col-sm-4', false, '', FALSE); ?>
            </div>
            <div class="col-sm-3">
                <?php echo Yii::t('app', 'FAT') ?>
                <?= $form->field($model, 'fat')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(FALSE); ?>
            </div>
            <div class="col-sm-3">
                <?php echo Yii::t('app', 'SNF') ?>
                <?= $form->field($model, 'snf')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(FALSE); ?>
            </div>
            <div class="form-group col-sm-3 mt15">

                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
                    'id' => 'recoveryBtn',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => $url,
                        'beforeSend' => new JsExpression('function(data) {
                                    $("#loadercontent").show();
                                    $("#pageloader").show();
                                }'),
                        'success' => new JsExpression('function(data){
                                                                 $("#loadercontent").hide();
                                                                 $("#pageloader").hide();    
                                                                var data=$.parseJSON(data);
                                                                if (data.status == "success"){ 
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>", function(){
                                                                          location.reload(); 
                                                                    });
                                                                }else{
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data.msg, function(key, val) {
                                                                        if(key != null){   
                                                                            $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        }
                                                                    });
                                                                    $(".error-summary").show();
                                                                   
                                                                }
                                                 }'),
                        'error' => new JsExpression('function(data) {
                                        $("#loadercontent").hide();
                                        $("#pageloader").hide();
                                }'),
                    ],
                    'options' => [
                        'class' => 'btn btn-default btn-raised',
                        'type' => 'submit'
                    ],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>

            </div>
        </div>
    </div>
    <br/>
    <?php ActiveForm::end(); ?>