<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$this->title = 'Import Android Files';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'validateOnBlur' => true,
                        'class' => 'popup-form',
                        'id' => 'import-android-files',
                        'enableAjaxValidation' => false,
                    ], 'fieldConfig' => [
        ]]);
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
                            'acceptedMimeTypes' => ".txt",
                            'url' => \yii\helpers\Url::to(['/collection/tbl-milk-collection/import-file']),
                            'addRemoveLinks' => true,
                            'autoDiscover' => false,
                            'maxFiles' => 20,
                            'maxFilesize' => 1,
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
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Upload'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => \yii\helpers\Url::to(['/collection/tbl-milk-collection/android-collection']),
                    'beforeSend' => new \yii\web\JsExpression('function(data){
                                            var allowSave = true;
                                            var file = $("#file_name").val();
//                                            var fileArray = file.split(",");
//                                              $.each(fileArray, function(index, value) {
//                                                var finalFile = value.substring(0, 6);
//                                                    if(allowSave && finalFile != "" && finalFile != undefined && finalFile != finalDate){
//                                                       bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>File Must be of selected Date</span></div></div>", function(result){
//                                                       });
//                                                       allowSave = false;
//                                                    }
//                                            }); 
                                            if(!allowSave) {
                                                return false;
                                            } else {
                                                $("#loadercontent").show();
                                                $("#pageloader").show();
                                            }
                                    }'),
                    'success' => new \yii\web\JsExpression('function(data){                                   
//                                            $("#pageloader").hide();
//                                            $("#loadercontent").hide();
                                            var obj1 = $.parseJSON(data);
//                                            console.log(data);
                                            if (obj1.status == "success"){
                                                $("#importModal").modal("toggle");
                                                $("#import-android-files")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                window.location.reload();                                           
                                           }else{
                                                $("#importModal").modal("toggle");
                                                $("#import-android-files")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
//                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.data+"</span></div></div>");
                                                window.location.reload();                                              
}
                             }'),
                    'error' => new \yii\web\JsExpression('function(){
                                    $("#pageloader").hide();
                                    $("#loadercontent").hide();
                                    if($("#file_name").val()==""){
                                     bootbox.alert("Please select file.");
                                    }else{
                                        $("#importModal").modal("toggle");
                                        $("#import-android-files")[0].reset();
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