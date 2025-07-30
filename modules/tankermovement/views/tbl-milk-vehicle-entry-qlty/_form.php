<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\View;
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
                $cnt = 1;
                foreach ($config_list as $c) {
                    echo Html::activeHiddenInput($config, '[' . $c->config_code . ']config_code', ['value' => $c->config_code]);
                    ?>
                    <div class="col-sm-2">
                        <?= $c->prepareControl($form, $config, $c->config_code); ?>
                    </div>
                    <?php if ($cnt == 6) { ?>
                        <?php
                        $cnt = 0;
                    }
                    ?>
                    <?php
                    $cnt++;
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
    
    $(document).ready(function() {
        checkQualityRanges();
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
                            $('#tblmilkvehicleentryqlty-clr').val(obj.data);
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
    
    $(document).on('change', '#tblmilkvehicleentryqlty-chamber_no', function() {
        fillData();
    });
    
    function fillData() {
        var chamber_no = $('#tblmilkvehicleentryqlty-chamber_no').val(); 
        if(setData(chamber_no)){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['get-quality-data']) . "',
                data: {'id' : chamber_no},
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if(obj1.status == 'success'){
                    var modelData = obj1.data.model; 
                    var configList = obj1.data.config_list;
                        $('#tblmilkvehicleentryqlty-record_status').val(modelData.record_status).trigger('change');
                        const modelFields = [
                            'acidity', 'mbrt','fat','snf','clr', 'water','density','protein','lactose','freezing_point', 'temp', 'tested_by', 'verified_by'
                        ];
                        modelFields.forEach(function(fieldName) {
                            $('#tblmilkvehicleentryqlty-' + fieldName).val(modelData[fieldName] || '');
                        });
                        $('#tblmilkvehicleentryqlty-sample_datetime').val(modelData.sample_datetime);
                        $('#tblmilkvehicleentryqlty-sample_time').val(obj1.data.sample_time);
                        $('.config_class').val('').trigger('change');
                        $.each(configList, function(key,val) {
                            $('#tblconfigtxnresult-'+val.config_code+'-config_result').val(val.config_result || '').trigger('change');
                        });
                    }
                },
            });
        }
    }
";
$this->registerJs($script, View::POS_END, 'milk-vehicle-entry-qlty-script');
?>
