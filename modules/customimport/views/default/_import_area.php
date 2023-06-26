<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$data = \app\modules\customimport\importData::getLabels($type);
$param = (!empty(Yii::$app->request->get('local_fields'))) ? Yii::$app->request->get('local_fields') : 'local_name';
//$selected= implode(',', $selected);
$required=!empty($required)?implode(',', $required):'';
//echo '<pre>';
//print_r($data);
//exit;
$readonly = false;
?>


<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= 'Import Data For '.$type  ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'validateOnBlur' => true,
                        'class' => 'popup-form',
                        'id' => 'import-form',
                        'enableAjaxValidation' => false,
                    ], 'fieldConfig' => [
        ]]);
        ?>
        <div class="modal-body">
            <?= Html::a('Download Sample', ['/customimport/default/download-sample', 'flag' => $type, 'local_field' => $param,'selected'=>$selected,'custom'=>false], ['class' => 'btn btn-primary']); ?>
            <?= Html::hiddenInput('mapping', 0, ['id' => 'mappingField']); ?>
            <div class="modal-msg">
                <h4><?= Yii::t('app', 'Upload file having fields in following manner') ?> :</h4>
                <p class="fields"><?php echo str_replace(',', ', ', $selected); ?></p>
                <p class="fields"><b><?= Yii::t('app', 'Mendatory Fields') ?> :</b> <?php echo str_replace(',', ', ', $required); ?></p>
            </div>
            <?php
            $i = 0;
            echo Html::hiddenInput('file_name', '', ['id' => 'file_name']);
            echo Html::hiddenInput('local_field', $param);
            ?>
            <?=
            Dropzone::widget([
                'id' => 'mainDrop',
                'options' => [
                    'acceptedMimeTypes' => ".csv,.xls,.xlsx",
                    'url' => \yii\helpers\Url::to(['/customimport/default/import-file',
                        'main' => 1,]),
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
                                            }
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                                
                                        }",
                    'removedfile' => "function(file){
                                                        $('#file_name').val('');
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
                    'url' => \yii\helpers\Url::to(['/customimport', 'flag' => $type, 'selected'=>$selected,'required'=>$required, 'type'=>$flag]),
                    'beforeSend' => new \yii\web\JsExpression('function(data){
                                            $("#loadercontent").show();
                                            $("#pageloader").show();
                                    }'),
                    'success' => new \yii\web\JsExpression('function(data){
                                            $("#upload-btn").attr("disabled",true);
                                            $("#pageloader").hide();
                                            $("#loadercontent").hide();
                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == "success"){
                                                $("#importModal").modal("toggle");
                                                $("#import-form")[0].reset();
                                                Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                            }else{
                                                $("#importModal").modal("toggle");
                                                $("#import-form")[0].reset();
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
                                        $("#import-form")[0].reset();
                                        Dropzone.forElement("#mainDrop").removeAllFiles(true);
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>You have error in your file</span></div></div>");
                                    }
                             }'),
                ],
                'options' => ['class' => 'btn btn-primary','id'=>'upload-btn','disabled'=>true,
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>