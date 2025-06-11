<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
?>
<?php $url = Url::to(['/tankermovement/tbl-milk-vehicle-entry-qlty/qlty-submit', 'TblMilkVehicleEntry' => ['trip_code' => $model->trip_code]]); ?>
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
        <h4 class="theme-box-heading " style="padding: 5px;">Compartment Wise Tanker Milk Lot Quality</h4>
        <div class="table-responsive padding_left_10">
            <div class="row">
                <div class="col-sm-2">
                    <?php echo Html::hiddenInput('status', 'pending', ['id' => 'tblmilkvehicleentryqlty-status']); ?>
                    <?= Yii::$app->dropdown->depend_dropdown('chamber_no', $model, $form, 'tblmilkvehicleentryqltysearch-trip_code,tblmilkvehicleentryqlty-status', 'form-group col-sm-4', $model->getAttributeLabel('chamber_no'), '', FALSE); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'acidity')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'mbrt')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'fat')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'snf')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'clr')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'water')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'density')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'protein')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'lactose')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2 number-validate">
                    <?= $form->field($model, 'freezing_point')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'temp')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'tested_by')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'verified_by')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2">
                    <?= Yii::$app->controls->date($model, $form, 'sample_datetime', '', date('Y-m-d'), false, FALSE, true); ?>
                </div>
                <div class="col-sm-1">
                    <?= $form->field($model, 'sample_time')->widget(MaskedInput::className(), ['mask' => '99:99']); ?>
                </div>
                <div class="col-sm-2"> 
                    <?= Yii::$app->dropdown->dropdownStatic('record_status', $model, $form, 'form-group', $model->getAttributeLabel('record_status'), false, 'record_status', false); ?>
                </div>
                <?php
                $index = 1;
                $cnt = 1;
                foreach ($config_list as $c) {
                    echo Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]);
                    ?>
                    <div class="col-sm-2">
                        <?= $c->prepareControl($form, $config, $index); ?>
                    </div>
                    <?php if ($cnt == 6) { ?>
                        <?php
                        $cnt = 0;
                    }
                    ?>
                    <?php
                    $cnt++;
                    $index++;
                }
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-sm-3 mt23">
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
</div>
<?php ActiveForm::end(); ?>