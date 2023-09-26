<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$this->title = 'BIPL Files Process';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
            'validateOnBlur' => true,
            'class' => 'popup-form',
            'id' => 'import-pendrive-packet',
            'enableAjaxValidation' => false,
        ], 'fieldConfig' => []]);
        ?>
        <div class="modal-body">
            <div class="row">
                <?php echo Html::hiddenInput('TblFtpTxnLog[file_name]', '', ['id' => 'file_name']); ?>
                <?php echo Html::hiddenInput('TblFtpTxnLog[date_file_name]', '', ['id' => 'date_file_name']); ?>
                <div class="col-sm-3">
                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
                </div>
                <div class="col-sm-12">
                    <?=
                    Dropzone::widget([
                        'id' => 'mainDrop',
                        'options' => [
                            'acceptedMimeTypes' => ".BDF",
                            'url' => \yii\helpers\Url::to(['/bkgprocess/tbl-ftp-txn-log/import-file']),
                            'addRemoveLinks' => true,
                            'autoDiscover' => false,
                            'maxFiles' => 50,
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
                                                
                                                var date = $('#date_file_name').val();
                                                $('#date_file_name').val(date+','+data.datefile);
                                               
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
                           
                                var datefile_str = $('#date_file_name').val();
                                var reslt = datefile_str.replace(','+file.name,''); 
                                $('#date_file_name').val(reslt);
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
            if ($matchingRecordsExist <= 0) {
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Upload'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => \yii\helpers\Url::to(['/bkgprocess/tbl-ftp-txn-log/bipl-pendrive-collection']),
                        'beforeSend' => new \yii\web\JsExpression('function(data){
                                    var file = $("#date_file_name").val(); 
                                
                                    if(file == ""){
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select File</span></div></div>", function(result){
                                        });
                                        return false;
                                    }
                                    var allowSave = true;
                                    if(!allowSave) {
                                        return false;
                                    } else {
                                        $("#loadercontent").show();
                                        $("#pageloader").show();
                                    }
                                }'),
                        'success' => new \yii\web\JsExpression('function(data){                                   
                                    $("#pageloader").hide();
                                    $("#loadercontent").hide();
                                    var obj1 = $.parseJSON(data);
                                    if (obj1.status == "success"){
                                        $("#importModal").modal("toggle");
                                        $("#import-pendrive-packet")[0].reset();
                                        Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                        var redirectUrl = "' . Url::to(['/bkgprocess/tbl-ftp-txn-log/list']) . '";
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>", function() {
                                            window.location = redirectUrl;
                                        });
                                    }else{
                                        $("#importModal").modal("toggle");
                                        $("#import-pendrive-packet")[0].reset();
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
                                        $("#import-pendrive-packet")[0].reset();
                                        Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                    }
                                }'),
                    ],
                    'options' => ['class' => 'btn btn-primary', 'id' => 'upload-btn', 'type' => 'submit', 'disabled' => true],
                ]);
                AjaxSubmitButton::end();
            }
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = "
            Dropzone.autoDiscover = false;
            ";
$this->registerJs($script, View::POS_END, 'import-manager');
?>