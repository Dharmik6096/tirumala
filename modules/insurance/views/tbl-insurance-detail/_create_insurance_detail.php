<?php

use app\components\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\Html;
use kato\DropZone;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
$form = ActiveForm::begin([
            'id' => 'insurance-disp-form',
            'validateOnBlur' => true,
            'errorCssClass' => 'error',
            'fieldConfig' => [
                'errorOptions' => ['class' => 'help-block'],
                'options' => ['class' => 'form-group col-md-12',
                    'id' => 'main_form'],
            ],
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('insurance_master_list', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('insurance_master_code'), $readonly); ?>
    </div>

    <div class="col-sm-12 import_area dropzone_height_130">
        <div id='sample_download' class="text-right">

        </div> 
        <div class="modal-msg">
            <h4><?= Yii::t('app', 'Upload file having fields in following manner') ?> :<span class="text-right "><?= Html::a('<i class="fa fa-download"></i>', ['/import/default/download-sample', 'flag' => 'insurance-detail', 'local_field' => ''], ['class' => 'btn btn-fab btn-danger btn-download pull-right', 'title' => 'Download Sample']); ?></span></h4>
            <p class="fields"><?php echo str_replace(',', ', ', 'sr_no,dcs_code,dcs_name,member_id,adhar_no,member_code,member_name,gender,dob,age,nominee_member_name,date_of_joining_scheme,nominee_adhar_no'); ?></p>
        </div>
            <div class="row">
                <?php
                echo Dropzone::widget([
                    'id' => 'mainDrop',
                    'options' => [
                        'acceptedMimeTypes' => ".xls,.xlsx",
                        'url' => Url::to(['import-insurance-detail']),
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
                        'id' => 'submit',
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['check-data-exists']), // Initial AJAX call to check-data-exists
                            'beforeSend' => new JsExpression("function(data){ 
                                var errMsg = '';
                                if($('#file_name').val()==''){                                                                                  
                                    errMsg += 'Please Attach File.';
                                }
                                if(errMsg != ''){
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>'+errMsg+'</span></div></div>');     
                                    return false;
                                }
                            }"),
                            'success' => new JsExpression('function(response) {
                                if(response.status == "error") {
                                    if(response.type == "draft") {
                                        bootbox.confirm({
                                            message: "<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>"+response.msg+"</span></div></div>",
                                            buttons: {
                                                cancel: {
                                                    label: "No",
                                                    className: "btn btn-danger"
                                                },
                                                confirm: {
                                                    label: "Yes",
                                                    className: "btn btn-primary",
                                                }
                                            },
                                            callback: function(result) {
                                                if(result) {
                                                    // Proceed with the second AJAX call
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "' . Url::to(['create-insurance-detail']) . '",
                                                        data: $("#insurance-disp-form").serialize(),
                                                        success: function(data) {
                                                            if (data.status == "success"){
                                                                window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                                            }else{
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                $("div.help-block").remove();
                                                                var cnt=0;
                                                                $.each(data, function(key, val) {
                                                                    $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                                    $("#"+key).closest(".form-group").addClass("error");
                                                                });
                                                                if(cnt==0 && typeof data.message != "undefined") 
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+data.message+"</span></div></div>");
                                                            }
                                                        },
                                                        error: function(xhr, status, error) {
                                                            // Handle error of the second AJAX call
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    } else {
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+response.msg+"</span></div></div>");
                                        return false;
                                    }
                                } else {
                                    $("#loadercontent").show();
                                    $("#pageloader").show();
                                    // Proceed with the second AJAX call
                                    $.ajax({
                                        type: "POST",
                                        url: "' . Url::to(['create-insurance-detail']) . '",
                                        data: $("#insurance-disp-form").serialize(),
                                        success: function(data) {
                                            if (data.status == "success"){
                                                window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                            }else{
                                                $("#loadercontent").hide();
                                                $("#pageloader").hide();
                                                $("div.help-block").remove();
                                                var cnt=0;
                                                $.each(data, function(key, val) {
                                                    $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                    $("#"+key).closest(".form-group").addClass("error");
                                                });
                                                if(cnt==0 && typeof data.message != "undefined") 
                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+data.message+"</span></div></div>");
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            // Handle error of the second AJAX call
                                        }
                                    });
                                }
                            }'),
                            'error' => new JsExpression('function(xhr, status, error) {
                                // Handle error of the first AJAX call
                            }'),
                        ],
                        'options' => ['class' => 'btn btn-primary', 'type' => 'button'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = "Dropzone.autoDiscover = false;";
$this->registerJs($script, View::POS_END, 'insurance');
?>