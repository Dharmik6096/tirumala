<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$param = (!empty(Yii::$app->request->get('local_fields'))) ? Yii::$app->request->get('local_fields') : 'local_name';
$readonly = false;
?>


<div class="modal modal-default fade" id="attachmentModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Attachment File'); ?></h4>
            </div>

            <?php
            $form = ActiveForm::begin(['options' => [
                            'validateOnBlur' => true,
                            'class' => 'popup-form',
                            'id' => 'attachment-form',
                            'enableAjaxValidation' => false,
                            'action' => 'abv',
                        ], 'fieldConfig' => [
            ]]);
            ?>
            <div class="modal-body">
                <?= Html::hiddenInput('eipl_app_feedback_master_code', $model->eipl_app_feedback_master_code, ['id' => 'eipl_app_feedback_master_code']); ?>
                <div class="modal-msg">
                    <h4><?= Yii::t('app', 'Upload file image and pdf only') ?></h4>
                </div>
                <?php
                $i = 0;
                echo Html::hiddenInput('filename', '', ['id' => 'file_name']);
                echo Html::hiddenInput('local_field', $param);
                ?>
                <?=
                DropZone::widget([
                    'id' => 'mainDrop',
                    'options' => [
                        'acceptedMimeTypes' => ".jpg,.jpeg,.png,.pdf",
                        'url' => Url::to(['/feedback/tbl-eipl-app-feedback-master/attachment-file',
                            'main' => 1,]),
                        'addRemoveLinks' => true,
                        'autoDiscover' => false,
                        'maxFiles' => 1,
                    ],
                    'clientEvents' => [
                        'success' => "function( file, response ){
                                            $('#saveBtn').attr('disabled',false);
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                                $('#file_name').val(data.msg);
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                                
                                        }",
                        'removedfile' => "function(file){
                                                        $('#file_name').val('');
                                                        $('#saveBtn').attr('disabled',true);
                                           }",
                        'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
                    ]
                ]);
                ?>

            </div>
            <div class="modal-footer">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['/feedback/tbl-eipl-app-feedback-master/upload-attachment-file']),
                        'beforeSend' => new \yii\web\JsExpression('function(data){
                                            $("#saveBtn").attr("disabled",true);
                                            $("#loadercontent").show();
                                            $("#pageloader").show();
                                    }'),
                        'success' => new \yii\web\JsExpression('function(data){
                                            $("#saveBtn").attr("disabled",true);
                                            $("#pageloader").hide();
                                            $("#loadercontent").hide();
                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == "success"){
//                                                window.location.reload();
                                                var currentdate = new Date();
                                                var hours = currentdate.getHours() < 10 ? "0"+currentdate.getHours() : currentdate.getHours();
                                                var minutes = currentdate.getMinutes() < 10 ? "0"+currentdate.getMinutes() : currentdate.getMinutes();
                                                var time = hours + ":" +minutes;
                                                var html = "<div class=\"sent-section\"><div class=\"sent\"><span class=\"messager_name\"><strong>"+obj1.data["name"]+"</strong></span><span class=\"message-time\">"+time+"</span><br><pre></pre><div class=\"feedback_image\">"+obj1.data["file"]+"</div></div><div class=\"triangle\"></div></div>";
                                                $("#chatboard").append(html);
                                                var objDiv = document.getElementById("chatboard");
                                                objDiv.scrollTop = objDiv.scrollHeight;   
                                                $("#attachmentModal").modal("toggle");
                                                $("#attachment-form")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
//                                                   window.location.reload();
//                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                }else{
                                                $("#attachmentModal").modal("toggle");
                                                $("#attachment-form")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><br/><div class=\'col-sm-10 padding-left-0 \'>"+obj1.data+"</div></div>");

//                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.data+"</span></div></div>");
                                            }
                             }'),
                        'error' => new \yii\web\JsExpression('function(){
                                    $("#saveBtn").attr("disabled",true);
                                    $("#pageloader").hide();
                                    $("#loadercontent").hide();
                                    if($("#file_name").val()==""){
                                     bootbox.alert("Please select file.");
                                    }else{
                                        $("#attachmentModal").modal("toggle");
                                        $("#attachment-form")[0].reset();
                                        Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>You have error in your file</span></div></div>");
                                    }
                             }'),
                    ],
                    'options' => ['class' => 'btn btn-primary', 'disabled'=>'true', 'id' => 'saveBtn',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = "    
    Dropzone.autoDiscover = false;
    $('.close-import').on('click',function(e){
        Dropzone.forElement('#mainDrop').removeAllFiles(true);
    });
";
$this->registerJs($script, View::POS_END, 'attachment_file');
?>