<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;

$url = Url::to(['/tankermovement/tbl-milk-vehicle-entry-qlty-merge/qlty-submit', 'TblMilkVehicleEntry' => ['trip_code' => $model->trip_code]]);
$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-group popup-form',
                'id' => 'milk-vehicle-entry-qlty-merge-form',
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
            <div class="row">
                <div class="col-sm-2">
                    <?= Html::hiddenInput('trip', $searchModel->trip_code, ['id' => 'trip']); ?>
                    <?= Html::hiddenInput('union', $searchModel->union_code, ['id' => 'union']); ?>
                    <?= Html::hiddenInput('vehicle', $trip_model->vehicle_code, ['id' => 'vehicle']); ?>
                    <?= Html::hiddenInput('plant', $trip_model->source_org_code, ['id' => 'plant']); ?>
                    <?= Yii::$app->dropdown->chamberNoList($model, $form, 'vehicle', 'chamber_no', Yii::t('app', 'Chamber No')); ?>
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
                    <?= $form->field($model, 'mbrt')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'temp')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-1 number-validate">
                    <?= $form->field($model, 'acidity')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'tested_by')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'verified_by')->textInput(['class' => 'form-control', 'autocomplete' => "off"])->label(); ?>
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
                    'id' => 'recoveryBtns',
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

    $(document).on('change', '#tblmilkvehicleentryqltymerge-fat, #tblmilkvehicleentryqltymerge-clr, #tblmilkvehicleentryqltymerge-snf, #tblmilkvehicleentryqltymerge-chamber_no', function() {
        calculateClr();
    });
    
    $(document).ready(function() {
        checkQualityRanges();
    });

    function calculateClr(){
        var fat = $('#tblmilkvehicleentryqltymerge-fat').val();
        var snf = $('#tblmilkvehicleentryqltymerge-snf').val();
        var clr = $('#tblmilkvehicleentryqltymerge-clr').val();
        var chamberNo = $('#tblmilkvehicleentryqltymerge-chamber_no').val();
        var tripCode = $('#tblmilkvehicleentryqltymergesearch-trip_code').val();

        is_clr_input == 0 && (fat == '' || snf == '') && $('#tblmilkvehicleentryqltymerge-clr').val('');
        is_clr_input == 1 && (fat == '' || clr == '') && $('#tblmilkvehicleentryqltymerge-snf').val('');

        if(((is_clr_input == 0 && setData(fat) && setData(snf)) || (is_clr_input ==1 && setData(fat) && setData(clr))) && setData(chamberNo) && setData(tripCode)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['calculate-clr']) . "',
                data: {'union_code':union,'fat':fat,'snf':snf,'clr':clr,'is_clr_input':is_clr_input,'plantCode':plantCode,'chamberNo':chamberNo,'tripCode':tripCode},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        if(is_clr_input==0) {
                            $('#tblmilkvehicleentryqltymerge-clr').val(obj.data);
                        } else {
                            $('#tblmilkvehicleentryqltymerge-snf').val(obj.data);
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
                    $(document).off('change', '#tblmilkvehicleentryqltymerge-fat, #tblmilkvehicleentryqltymerge-snf, #tblmilkvehicleentryqltymerge-clr').on('change', '#tblmilkvehicleentryqltymerge-fat, #tblmilkvehicleentryqltymerge-snf, #tblmilkvehicleentryqltymerge-clr', function () {
                        var fat = parseFloat($('#tblmilkvehicleentryqltymerge-fat').val());
                        var snf = parseFloat($('#tblmilkvehicleentryqltymerge-snf').val());
                        var clr = parseFloat($('#tblmilkvehicleentryqltymerge-clr').val());

                        if (!isNaN(fat) && (fat < minFat || fat > maxFat)) {
                            showError('FAT', minFat, maxFat);
                            $('#tblmilkvehicleentryqltymerge-fat').val('');
                        }
                        if (!$('#tblmilkvehicleentryqltymerge-snf').is('[readonly]')) {
                            var snf = parseFloat($('#tblmilkvehicleentryqltymerge-snf').val());
                            if (!isNaN(snf) && (snf < minSnf || snf > maxSnf)) {
                                showError('SNF', minSnf, maxSnf);
                                $('#tblmilkvehicleentryqltymerge-snf').val('');
                            }
                        }
                        if (!$('#tblmilkvehicleentryqltymerge-clr').is('[readonly]')) {
                            var clr = parseFloat($('#tblmilkvehicleentryqltymerge-clr').val());
                            if (!isNaN(clr) && (clr < minClr || clr > maxClr)) {
                                showError('CLR', minClr, maxClr);
                                $('#tblmilkvehicleentryqltymerge-clr').val('');
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
$this->registerJs($script, View::POS_END, 'milk-vehicle-entry-qlty-merge-script');
?>