<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>
<?php $url = Url::to(['/tankermovement/tbl-milk-vehicle-entry-qlty/qlty-update', 'TblMilkVehicleEntry' => ['trip_code' => $model->trip_code]]); ?>
<?php
$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-group popup-form',
                'id' => 'milk-vehicle-entry-qlty-form',
            ],
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix pb15">
        <h4 class="theme-box-heading">Tanker Milk Lot Quality</h4>
    </div>
    <div class="col-sm-2">
        <?php echo Html::hiddenInput('milk_vehicle_entry_qlty_code', $model->milk_vehicle_entry_qlty_code, ['id' => 'tblmilkvehicleentryqlty-milk_vehicle_entry_qlty_code']); ?>
        <?= $form->field($model, 'chamber_no')->textInput(['class' => 'form-control', 'autocomplete' => "off", 'readonly' => TRUE])->label(); ?>
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
        <?= $form->field($model, 'mbrt')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'temp')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'acidity')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <?php
    $index = 1;
    $cnt = 1;
    foreach ($config_list as $c) {
        echo Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]);
        ?>
        <div class="col-sm-2">
            <?php
            $config_mapping = $c->getConfigResultTxnList((string) $model->milk_vehicle_entry_qlty_code);
            $config_result = !empty($config_mapping->config_result) ? $config_mapping->config_result : '0';
            ?>
            <?= $form->field($config, '[' . $index . ']config_result')->textInput(['value' => $config_result])->label(Yii::t('app', $c->config_name)); ?>

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
<div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Edit'),
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
<?php ActiveForm::end(); ?> 