<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$this->title = 'Upload Documents';
?>

<div class="modal fade in popup_modal" id="ImportAttachementsModel" role="dialog">
    <div class="modal-dialog w750 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title"><?= $this->title ?></h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                <?php
                    $form = ActiveForm::begin(['options' => [
                                    'validateOnBlur' => true,
                                    'class' => 'popup-form',
                                    'id' => 'upload-images-documents',
                                    'enableAjaxValidation' => false,
                                ], 'fieldConfig' => [
                    ]]);
                    ?>
                    <div class="modal-body">
                        <div class="row">
                            <?php echo Html::activeHiddenInput($model, 'file_name', $options = ['id' => 'file_name']);?>
                            <?php echo Html::activeHiddenInput($model, 'customer_code', $options = ['id' => 'customer_code']);?>
                            <div class="col-sm-12">
                                <?=
                                Dropzone::widget([
                                    'id' => 'mainDrop',
                                    'options' => [
                                        'acceptedMimeTypes' => ".jpg,.pdf,.jpeg,.png",
                                        'url' => \yii\helpers\Url::to(['/organisation/tbl-customer-master/import-file']),
                                        'addRemoveLinks' => true,
                                        'autoDiscover' => false,
                                        'maxFiles' => 5,
                                        'maxFilesize' => 2,
                                    //  'maxTotalSize' => 0.0009,
                                    ],
                                    'clientEvents' => [
                                        'success' => "function( file, response ){
                                                        var data=$.parseJSON(response);
                                                        if(data.status=='success')
                                                        { 
                                                            var file = $('#file_name').val();
                                                            $('#file_name').val(file+','+data.msg);
                                                            $('#upload-btn').attr('disabled',false);
                                                        } else {
                                                            $(file.previewElement).remove();
                                                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                                        }
                                                            
                                                    }",
                                        'removedfile' => "function(file){
                                    var file_str = $('#file_name').val();
                                    var res = file_str.replace(file_str,''); 
                                    $('#file_name').val(res);
                                                    }",
                                        'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php
                        AjaxSubmitButton::begin([
                            'label' => Yii::t('app', 'Upload'),
                            'ajaxOptions' => [
                                'type' => 'POST',
                                'url' => \yii\helpers\Url::to(['/organisation/tbl-customer-master/import-attachements', 'id' => $model->customer_code]),
                                'beforeSend' => new \yii\web\JsExpression('function(data){
                                                        $("#loadercontent").show();
                                                        $("#pageloader").show();
                                                }'),
                                'success' => new \yii\web\JsExpression('function(data){                                   
                                                        $("#pageloader").hide();
                                                        $("#loadercontent").hide();
                                                        var obj1 = $.parseJSON(data);
                                                        if (obj1.status == "success"){
                                                            $("#ImportAttachementsModel").modal("toggle");
                                                            $("#upload-images-documents")[0].reset();
                                                            Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                        }else{
                                                            $("#ImportAttachementsModel").modal("toggle");
                                                            $("#upload-images-documents")[0].reset();
                                                            Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                        }
                                        }'),
                                'error' => new \yii\web\JsExpression('function(){
                                                $("#pageloader").hide();
                                                $("#loadercontent").hide();
                                                if($("#file_name").val()==""){
                                                bootbox.alert("Please select file.");
                                                }else{
                                                    $("#ImportAttachementsModel").modal("toggle");
                                                    $("#upload-images-documents")[0].reset();
                                                    Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                }
                                        }'),
                            ],
                            'options' => ['class' => 'btn btn-primary', 'id' => 'upload-btn', 'type' => 'submit', 'disabled' => true,],
                        ]);
                        AjaxSubmitButton::end();
                        ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>