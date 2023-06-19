<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'staff-member-form', 'class' => 'pull-left'],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= Html::activeHiddenInput($model, 'salary'); ?> 
    <div class="col-sm-4 padding_right_0">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-5 padding_right_0">
        <?=
        $form->field($model, 'month')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control '],
            'mask' => '99-9999',])
        ?>    
    </div>
    <div class="col-sm-3 mt20 pull-left">
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
                                                                    var message = "";
                                                                    $.each(data, function(key, val) {
//                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        message = message + val + "\r\n";
                                                                    });
//                                                                    $(".error-summary").show();
                                                                    
                                                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+message+"</span></div></div>");

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

