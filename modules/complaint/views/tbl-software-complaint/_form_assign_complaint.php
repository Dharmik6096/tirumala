<?php

use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['/complaint/tbl-software-complaint/remove']);
$path = Yii::$app->params['software_complaint_dir_path'];
file_exists($path . $txnModel->attachment) ? $size = filesize($path . $txnModel->attachment) : $size = '';
$engineer = \Yii::$app->session->get('isEngineer');
$user = \Yii::$app->session->get('UserCode');
$disableUser = '';
if ($engineer) {
    $txnModel->assign_to = $user;
    $disableUser = 'disable_div';
}
?>

<div class="col-sm-2 <?= $disableUser ?>">
    <?= Yii::$app->dropdown->dropdown('user', $txnModel, $form, '', TRUE, FALSE, 'assign_to'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($txnModel, $form, 'assign_date'); ?>
</div>
<div class="col-sm-2">
    <?=
    $form->field($txnModel, 'assign_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
        'mask' => '99:99',])
    ?> 
</div>
<div class="col-sm-2">
    <?= $form->field($txnModel, 'assign_remarks')->textInput() ?>
</div>
<div class="clearfix"></div>
<div class="col-sm-12">
    <?php echo Html::hiddenInput('old_attachment', $txnModel->attachment, ['id' => 'old_attachment']); ?>
    <?php echo Html::hiddenInput('attachment', $txnModel->attachment, ['id' => 'attachment']); ?>
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
                        if('$txnModel->attachment' != '' && '{$size}' != ''){
                            var data = '$path'+'$txnModel->attachment';
                            var mockFile = {
                                name: '$txnModel->attachment',
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



