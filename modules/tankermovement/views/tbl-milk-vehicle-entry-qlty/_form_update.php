<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use yii\web\View;
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
        <?= $form->field($model, 'acidity')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'mbrt')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'fat')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'snf')->textInput(['class' => 'form-control', 'autocomplete' => "off", 'readonly' => $model->is_clr_input == 1 ? true : false])->label(); ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'clr')->textInput(['class' => 'form-control', 'autocomplete' => "off", 'readonly' => $model->is_clr_input == 0 ? true : false])->label(); ?>
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
        <?= $form->field($model, 'sample_time')->widget(MaskedInput::className(), ['mask' => '99:99', 'options' => ['class' => 'form-control 24_hour_time_input', 'placeholder' => 'HH:MM']]); ?>
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
            <?php
            $config_mapping = $c->getConfigResultTxnList((string) $model->milk_vehicle_entry_qlty_code);
            $config_result = !empty($config_mapping->config_result) ? $config_mapping->config_result : '0';
            ?>
            <?= $c->prepareControl($form, $config_mapping, $index); ?>

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
                'class' => 'btn btn-default btn-raised btn-login',
                'type' => 'submit'
            ],
        ]);
        AjaxSubmitButton::end();
        ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?> 

    </div>
</div>
<?php ActiveForm::end(); ?> 
<?php
$script = "
    var union = `$model->union_code`;
    var is_clr_input = `$model->is_clr_input`;
    var plantCode = `$model->plant_code`;

    $(document).on('change', '#tblmilkvehicleentryqlty-fat, #tblmilkvehicleentryqlty-clr, #tblmilkvehicleentryqlty-snf', function() {
        calculateClr();
    });

    function calculateClr(){
        var fat = $('#tblmilkvehicleentryqlty-fat').val();
        var snf = $('#tblmilkvehicleentryqlty-snf').val();
        var clr = $('#tblmilkvehicleentryqlty-clr').val();

        is_clr_input == 0 && (fat == '' || snf == '') && $('#tblmilkvehicleentryqlty-clr').val('');
        is_clr_input == 1 && (fat == '' || clr == '') && $('#tblmilkvehicleentryqlty-snf').val('');

        if(((is_clr_input == 0 && setData(fat) && setData(snf)) || (is_clr_input ==1 && setData(fat) && setData(clr)))){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['calculate-clr']) . "',
                data: {'union_code':union,'fat':fat,'snf':snf,'clr':clr,'is_clr_input':is_clr_input,'plantCode':plantCode},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        if(is_clr_input==0) {
                            $('#tblmilkvehicleentryqlty-clr').val(obj.data.toFixed(2));
                        } else {
                            $('#tblmilkvehicleentryqlty-snf').val(obj.data);
                        }
                    }
                },
                error:function(data){
                }
            });
        }
    }

    // $(document).on('change', '#tblmilkvehicleentryqlty-milk_type_code', function() {
    //     checkQualityRanges();
    // });

    function checkQualityRanges(){
        $.ajax({
            type: 'post',
            url:'" . Url::to(['get-quality-param-range']) . "',
            data: {'union':union, 'plantCode':plantCode},
            success: function(data) {  
                var obj = $.parseJSON(data);
                if (obj.status == 'success') {
                    var range = obj.data;
                    var minFat = parseFloat(range.min_fat);
                    var maxFat = parseFloat(range.max_fat);
                    var minSnf = parseFloat(range.min_snf);
                    var maxSnf = parseFloat(range.max_snf);
                    var minClr = parseFloat(range.min_clr);
                    var maxClr = parseFloat(range.max_clr);
                    function showError(fieldName, min, max) {
                        var msg = fieldName + ' should be between ' + min + ' and ' + max;
                        bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                    }
                    $(document).off('change', '#tblmilkvehicleentryqlty-fat, #tblmilkvehicleentryqlty-snf, #tblmilkvehicleentryqlty-clr').on('change', '#tblmilkvehicleentryqlty-fat, #tblmilkvehicleentryqlty-snf, #tblmilkvehicleentryqlty-clr', function () {
                        var fat = parseFloat($('#tblmilkvehicleentryqlty-fat').val());
                        var snf = parseFloat($('#tblmilkvehicleentryqlty-snf').val());
                        var clr = parseFloat($('#tblmilkvehicleentryqlty-clr').val());

                        if (!isNaN(fat) && (fat < minFat || fat > maxFat)) {
                            showError('FAT', minFat, maxFat);
                            $('#tblmilkvehicleentryqlty-fat').val('');
                        }
                        if (!$('#tblmilkvehicleentryqlty-snf').is('[readonly]')) {
                            var snf = parseFloat($('#tblmilkvehicleentryqlty-snf').val());
                            if (!isNaN(snf) && (snf < minSnf || snf > maxSnf)) {
                                showError('SNF', minSnf, maxSnf);
                                $('#tblmilkvehicleentryqlty-snf').val('');
                            }
                        }
                        if (!$('#tblmilkvehicleentryqlty-clr').is('[readonly]')) {
                            var clr = parseFloat($('#tblmilkvehicleentryqlty-clr').val());
                            if (!isNaN(clr) && (clr < minClr || clr > maxClr)) {
                                showError('CLR', minClr, maxClr);
                                $('#tblmilkvehicleentryqlty-clr').val('');
                            }
                        }
                    });
                }
            },
            error:function(data){
            }
        }); 
    };

    function setData(field = '') {
        if (field !== '' && field !== null && field !== undefined && field !== 'Loading ...') {
            return true;
        } else {
            return false;
        }
    }
";
$this->registerJs($script, View::POS_END, 'milk-vehicle-entry-qlty-update-script');
?>