<?php

use yii\bootstrap\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;

$form = ActiveForm::begin([
    'id' => 'import-provisional-member-bank-receipt-form',
    'validateOnBlur' => true,
    'errorCssClass' => 'error',
    'fieldConfig' => [
        'errorOptions' => ['class' => 'help-block'],
        'options' => [
            'class' => 'form-group col-md-12',
            'id' => 'main_form'
        ],
    ],
]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box theme_border_right">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('mode_of_payment', $model, $form, 'form-group', $model->getAttributeLabel('mode_of_payment'), false, 'mode_of_payment', false); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'bank_name')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'deposit_date', '', true); ?>
        </div>

        <div class="col-sm-12 import_area dropzone_height_130">
            <div class="row">
                <?php
                echo Dropzone::widget([
                    'id' => 'mainDrop',
                    'options' => [
                        'acceptedMimeTypes' => ".xls,.xlsx",
                        'url' => Url::to(['import-bank-receipt-detail']),
                        'addRemoveLinks' => true,
                        'autoDiscover' => false,
                        'maxFiles' => 1,
                    ],
                    'clientEvents' => [
                        'success' => "function(file, response) {
                        var data = $.parseJSON(response);
                        if (data.status == 'success') {
                            $('#file_name').val(data.msg);
                        } else {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>' + data.msg + '</span></div></div>');
                        }
                    }",
                        'removedfile' => "function(file) {
                        $('#file_name').val('');
                    }",
                        'sending' => "function(file, xhr, formData) {
                        formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "');
                    }"
                    ]
                ]);
                ?>
            </div>
        </div>
        <?= Html::hiddenInput('file_name', '', ['id' => 'file_name']); ?>
        <div class="col-sm-2 padding_top_20">
            <div class="form-group">
                <div class="col-md-12 top-bottom-15 padding-50">
                    <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Save'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['import-provisional-member-bank-receipt']),
                            'beforeSend' => new JsExpression("function(data){
                                var errMsg = '';
                                if($('#file_name').val()==''){                                                                                  
                                    errMsg += 'Please Attach File.';
                                }
                                if(errMsg != ''){
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>'+errMsg+'</span></div></div>');     
                                    return false;
                                } else {
                                    $('#loadercontent').show();
                                    $('#pageloader').show();
                                }
                            }"),
                            'success' => new JsExpression('function(data){
                                var data=$.parseJSON(data);
                                $(\'#loadercontent\').hide();
                                $(\'#pageloader\').hide();
                                if (data.status == "success"){ 
                                    $(".help-block").text("");
                                    $(".form-group").removeClass("has-error");         
                                    $(".error-summary").hide();
                                    $(".error-summary li").remove();
                                }else{
                                    $(".help-block").text("");
                                    $(".form-group").removeClass("has-error");
                                    $(".error-summary").hide();
                                    $(".error-summary li").remove();
                                    $.each(data, function(key, val) {
                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                    });
                                    $(".error-summary").show();
                                    // if(cnt==0 && typeof data.message != "undefined") 
                                    //     bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+data.message+"</span></div></div>");
                                }
                    }'),
                        ],
                        'options' => [
                            'class' => 'btn btn-default btn-raised',
                            'type' => 'submit'
                        ],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>