<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMember */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['/complaint/tbl-software-complaint/remove']);
$path = Yii::$app->params['software_complaint_dir_path'];
file_exists($path . $model->attachment) ? $size = filesize($path . $model->attachment) : $size = '';
$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin();
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">

    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblsoftwarecomplaint-union_code', '', 'Society Name', 'dcs_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'contact_person')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'contact_person_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'complaint_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('complaint_type', $model, $form, '', $model->getAttributeLabel('complaint_type'), false, 'complaint_type') ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('priority', $model, $form, '', $model->getAttributeLabel('priority'), false, 'priority') ?> 
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= $form->field($model, 'location')->textarea() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'complaint_desc')->textarea() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <?php if ($type == 'edit' && $model->complaint_status == 4) { ?>
        <div class="col-sm-1 mt15">
            <?= $form->field($model, 'is_chargeable', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'amount')->textInput() ?>
        </div>
        <div class="col-sm-2 number-validate">
            <?= $form->field($model, 'km')->textInput() ?>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <?php echo Html::hiddenInput('old_attachment', $model->attachment, ['id' => 'old_attachment']); ?>
        <?php echo Html::hiddenInput('attachment', $model->attachment, ['id' => 'attachment']); ?>
        <?=
        Dropzone::widget([
            'id' => 'mainDrop',
            'options' => [
                'acceptedMimeTypes' => ".jpg,.pdf,.jpeg,.png",
                'url' => \yii\helpers\Url::to(['/complaint/tbl-software-complaint/upload-file',
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
            <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])  ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
