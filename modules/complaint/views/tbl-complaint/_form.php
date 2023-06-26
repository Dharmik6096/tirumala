<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kato\DropZone;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMember */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['/complaint/tbl-complaint/remove', 'model' =>$model]);
$path = Yii::$app->params['complaint_dir_path'];
file_exists($path.$model->attachment) ? $size = filesize($path.$model->attachment) : $size = '';
?>

<?php
$form = ActiveForm::begin();
$complaint_type = array('None' => 'None','Network' => 'Network', 'Modem' => 'Modem', 'Eco' => 'Eko', 'Weigh Scale' => 'Weigh Scale');
$status = array('Created' => 'Create', 'Processing' => 'Processing', 'Resolved' => 'Resolved');
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblcomplaint-union_code', '', 'Society Name'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'contact_person')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'complaint_type')->dropdownList($complaint_type, ['prompt'=>'Select Complaint Type']); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'status')->dropdownList($status); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'affects_data', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <?php echo Html::hiddenInput('old_attachment', $model->attachment, ['id' => 'old_attachment']); ?>
        <?php echo Html::hiddenInput('attachment', $model->attachment, ['id' => 'attachment']); ?>
        <?=
            Dropzone::widget([
                'id' => 'mainDrop',
                'options' => [
//                    'acceptedMimeTypes' => ".csv,.xls,.xlsx",
                    'url' => \yii\helpers\Url::to(['/complaint/tbl-complaint/upload-file',
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
                                                            data: {'id':name,'value':val},
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
    <div class="col-sm-12 shortcut-main mt25" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
            <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
