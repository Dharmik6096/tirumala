<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMember */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['/complaint/tbl-complaint/remove']);
$path = Yii::$app->params['complaint_dir_path'];
file_exists($path.$complaint_model->attachment) ? $size = filesize($path.$complaint_model->attachment) : $size = '';
//var_dump($model->attachment);die;
?>

<?php
$form = ActiveForm::begin();
$complaint_type = array('None' => 'None','Network' => 'Network', 'Modem' => 'Modem', 'Eko' => 'Eko', 'Weigh Scale' => 'Weigh Scale');
$status = array('Processing' => 'Processing', 'Resolved' => 'Resolved');
$start_date = Yii::$app->controls->view_date($model->complaintCode->date);
//$start_date = strtotime("1 day", strtotime($model->complaintCode->date));
//$start_date = Yii::$app->controls->view_date(date("Y-m-d", $start_date));
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    
<!--    <div class="col-sm-3" id="union">
        <? = Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <? = Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblcomplaint-union_code', '', 'Society Name','',$disabled); ?>
    </div>-->
    <?= $form->field($model, 'complaint_code')->hiddenInput(['value'=>''])->label(false); ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person')->textInput() ?>
    </div> 
    <div class="col-sm-3">
       <?php echo $form->field($model, 'status')->dropdownList($status); ?>
    </div>
    <div class="col-sm-3">
       <?php echo $form->field($model, 'issue_type')->dropdownList($complaint_type, ['prompt'=>'Select Complaint Type']); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'date', '', '', $start_date); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>   
    <div class="col-sm-3 mt25">
        <?= $form->field($model, 'affects_data', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <?php echo Html::hiddenInput('old_attachment', $complaint_model->attachment, ['id' => 'old_attachment']); ?>
        <?php echo Html::hiddenInput('attachment', $complaint_model->attachment, ['id' => 'attachment']); ?>
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
                        if('$complaint_model->attachment' != ''){
                            var data = '$path'+'$complaint_model->attachment';
                            var mockFile = {
                                name: '$complaint_model->attachment',
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
    <div class="col-sm-12 shortcut-main mt25" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('edit'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>