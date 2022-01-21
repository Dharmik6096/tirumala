<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$this->title = 'EIPL Collection Zip File';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin(['options' => [
                        'validateOnBlur' => true,
                        'class' => 'popup-form',
                        'id' => 'import-pendrive-packet-zip',
                        'enableAjaxValidation' => false,
                    ], 'fieldConfig' => [
        ]]);
        ?>
        <div class="modal-body">
            <div class="row">
                <?php echo Html::hiddenInput('TblEiplPacketFileLog[file_name]', '', ['id' => 'file_name']); ?>
                <div class="col-sm-3">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
                </div>
                <div class="col-sm-12">
                    <?=
                    Dropzone::widget([
                        'id' => 'mainDrop',
                        'options' => [
                            'acceptedMimeTypes' => ".zip",
                            'url' => \yii\helpers\Url::to(['/eipldpu/pendrive-import/import-zip']),
                            'addRemoveLinks' => true,
                            'autoDiscover' => false,
                            'maxFiles' => 1,
                        ],
                        'clientEvents' => [
                            'success' => "function( file, response ){
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                            { 
                                                $('#file_name').val(data.msg);
                                                $('#upload-btn').attr('disabled',false);
                                            } else {
                                                $(file.previewElement).remove();
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                            }
                                                
                                        }",
                         'removedfile' => "function(file){
                          var file_str = $('#file_name').val();
                          var res = file_str.replace(file.name,''); 
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
                    'url' => \yii\helpers\Url::to(['/eipldpu/pendrive-import/add-zip']),
                    'beforeSend' => new \yii\web\JsExpression('function(data){
                                            $("#loadercontent").show();
                                            $("#pageloader").show();
                                    }'),
                    'success' => new \yii\web\JsExpression('function(data){                                   
                                            $("#pageloader").hide();
                                            $("#loadercontent").hide();
                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == "success"){
                                                $("#importModal").modal("toggle");
                                                $("#import-pendrive-packet-zip")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                            }else{
                                                $("#importModal").modal("toggle");
                                                $("#import-pendrive-packet-zip")[0].reset();
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
                                        $("#importModal").modal("toggle");
                                        $("#import-pendrive-packet-zip")[0].reset();
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