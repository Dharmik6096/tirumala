<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'staff-member-form'],
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= Html::activeHiddenInput($model, 'salary'); ?> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'month')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control '],
            'mask' => '99-9999',])
        ?>    
    </div>
    <div class="col-sm-2 mt20">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Process'),
            'id' => 'process',
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create']),
                'beforeSend' => new JsExpression("function(data){
                                var month = $('#tblstaffsalaryprocess-month').val();
                                $('#tblstaffsalaryprocess-disbursement_date').val('');
                                if(month == '') {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Enter Valid Month</span></div></div>');
//                                  bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Enter Valid Month') . "</span>');
                                    $('#tblstaffsalaryprocess-salary').val('');                                  
                                    return false;                        
                                }else{
                                    $('#tblstaffsalaryprocess-salary').val('process'); 
                                }
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $(".error-summary").hide();
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    reloadGrid();
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }else{
                                                                 $("#tblstaffsalaryprocess-salary").val("");
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
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'disbursement_date', 'form-group col-sm-3', true, '', false); ?>
    </div>
    <div class="col-sm-2 mt20">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Disburse'),
            'id' => 'disburse',
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create', 'type' => 'disburse']),
                'beforeSend' => new JsExpression("function(data){
                                var month = $('#tblstaffsalaryprocess-month').val();
                                var date = $('#tblstaffsalaryprocess-disbursement_date').val();
                                var process = $('#tblstaffsalaryprocess-salary').val(); 
                                if(month == '' || process == '') {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Disburse Not Allow before Process.</span></div></div>');
                                    return false;                        
                                }
                                if(date == '') {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Enter Disburse Date.</span></div></div>');
                                    return false;                        
                                }
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $(".error-summary").hide();
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    reloadGrid();
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
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
    </div>
</div>

<?php ActiveForm::end(); ?>

