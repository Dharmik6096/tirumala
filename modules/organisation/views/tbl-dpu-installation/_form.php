<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kato\DropZone;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDpuInstallation */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['/organisation/tbl-dpu-installation/remove']);
$path = Yii::$app->params['dpu_docs_path'];
file_exists($path.$model->attachment) ? $size = filesize($path.$model->attachment) : $size = '';
?>

<div class="tbl-dpu-installation-form">

    <?php $form = ActiveForm::begin([
    'options' => [],
    'validateOnBlur' => FALSE,
    'validateOnChange' => FALSE,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
]); ?>
    <?php echo $form->errorSummary($model); ?>

    <?= Html::activeHiddenInput($model, 'inst_code') ?>
    
    <?= Html::activeHiddenInput($model, 'union_code') ?>

    <?= Html::activeHiddenInput($model, 'dcs_code') ?>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'inst_date'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'inst_by')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'simcard_company')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'dpu_sim_mobile')->textInput(['class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'soc_secretary')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'secretary_mobile')->textInput(['class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-2 mt35">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <?php //= $form->field($model, 'attachment')->fileInput() ?>
        <?php echo Html::hiddenInput('old_attachment', $model->attachment, ['id' => 'old_attachment']); ?>
        <?php echo Html::hiddenInput('attachment', $model->attachment, ['id' => 'attachment']); ?>
        <?=
            Dropzone::widget([
                'id' => 'mainDrop',
                'options' => [
//                    'acceptedMimeTypes' => ".csv,.xls,.xlsx",
                    'url' => \yii\helpers\Url::to(['/organisation/tbl-dpu-installation/upload-file',
                        'main' => 1,]),
                    'addRemoveLinks' => true,
                    'autoDiscover' => false,
                    'maxFiles' => 1,
                    'init' => new JsExpression("function(file){
                        if('$model->attachment' != '' && '{$size}' != ''){
                            var data = '$path'+'$model->attachment';
                            var mockFile = {
                                name: '$model->attachment',
                                size: '{$size}',
                            };
                            mockFile.isMock = true;

                            // Tell eveyone this file was accepted.
                            mockFile.status = Dropzone.ADDED;
                            mockFile.accepted = true;
                            this.emit('addedfile', mockFile);
                            this.emit('success', mockFile);
                            this.emit('complete', mockFile);
                            this.options.maxFiles--;
    //                        var myDropzone = $('#mainDrop').dropzone;
    //                        console.log(myDropzone);
                    }
                   }"),
                ],
                'clientEvents' => [
                    'success' => "function( file, response ){
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                            { 
                                                $('#attachment').val(data.msg);
                                                $('#upload-btn').attr('disabled',false);
                                            }
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                                
                                        }",
                    'removedfile' => "function(file){
                                                        var name = file.name;
                                                        var val = $('#attachment').val();
                                                        var old_val = $('#old_attachment').val();
                                                        $.ajax({
                                                            type: 'POST',
                                                            'url': '{$url}',
                                                            data: {'id':name,'value':val,'old_value':old_val},
                                                            success: function(data) {  
                                                            var obj1 = $.parseJSON(data);
                                                            },
                                                            error:function(data){
                                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>File Not Removed Due to Error</span></div></div>');
                                                            }
                                                        });
                                                        $('#attachment').val('');
                                                        this.options.maxFiles++;
                                           }",
                    'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
                ]
            ]);
            ?>
    </div>    
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main mt25" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
