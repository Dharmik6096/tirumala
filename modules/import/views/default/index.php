<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use kato\DropZone;

$data = \app\modules\import\importData::getLabels($type);
$param = (!empty(Yii::$app->request->get('local_fields'))) ? Yii::$app->request->get('local_fields') : 'local_name';
//echo '<pre>';
//print_r($data);
//exit;
$readonly = false;
$appendId = !empty($appendId) ? $appendId : '';
?>


<div class="modal modal-default fade" id="importModal<?= $appendId ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Import File'); ?></h4>
            </div>

            <?php
            $form = ActiveForm::begin(['options' => [
                            'validateOnBlur' => true,
                            'class' => 'popup-form',
                            'id' => 'import-form' . $appendId,
                            'enableAjaxValidation' => false,
                        ], 'fieldConfig' => [
            ]]);
            ?>
            <div class="modal-body">
                <div id='sample_download'>
                    <?= Html::a('Download Sample', ['/import/default/download-sample', 'flag' => $type, 'local_field' => $param], ['class' => 'btn-login btn btn-primary']); ?>
                </div>    
                <?= Html::hiddenInput('mapping', 0, ['id' => 'mappingField' . $appendId]); ?>
                <div class="modal-msg">
                    <h4><?= Yii::t('app', 'Upload file having fields in following manner') ?> :</h4>
                    <p class="fields"><?php echo str_replace(',', ', ', $data['fields']); ?></p>
                </div>
                <?php
                $i = 0;
                echo Html::hiddenInput('file_name', '', ['id' => 'file_name' . $appendId]);
                echo Html::hiddenInput('local_field', $param);
                ?>
                <?php if ($type == 'member_limited') { ?>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-sm-4" id="union">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmember-union_code', '', 'Society', $readonly); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
                <?=
                DropZone::widget([
                    'id' => 'mainDrop' . $appendId,
                    'dropzoneContainer' => 'mainDrop' . $appendId,
                    'previewsContainer' => $appendId,
                    'options' => [
                        'acceptedMimeTypes' => ".csv,.xls,.xlsx",
                        'url' => Url::to(['/import/default/import-file',
                            'main' => 1,]),
                        'addRemoveLinks' => true,
                        'autoDiscover' => false,
                        'maxFiles' => 1,
                    ],
                    'clientEvents' => [
                        'success' => "function( file, response ){
                                            var appendId = '" . $appendId . "';
                                            var data=$.parseJSON(response);
                                            if(data.status=='success')
                                                $('#file_name'+appendId).val(data.msg);
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                                        }",
                        'removedfile' => "function(file){
                                                        $('#file_name'+appendId).val('');
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
                        'url' => Url::to(['/import', 'flag' => $type]),
                        'beforeSend' => new \yii\web\JsExpression('function(data){
                                            $("#loadercontent").show();
                                            $("#pageloader").show();
                                    }'),
                        'success' => new \yii\web\JsExpression('function(data){
                                            var appendId = "' . $appendId . '";
                                            $("#pageloader").hide();
                                            $("#loadercontent").hide();
                                            var obj1 = $.parseJSON(data);
                                            if (obj1.status == "success"){
                                                $("#importModal' . $appendId . '").modal("toggle");
                                                $("#import-form' . $appendId . '")[0].reset();
                                                Dropzone.forElement("#mainDrop' . $appendId . '").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+obj1.data+"</span></div></div>");
                                            }else{
                                                $("#importModal' . $appendId . '").modal("toggle");
                                                $("#import-form' . $appendId . '")[0].reset();
                                                Dropzone.forElement("#mainDrop' . $appendId . '").removeAllFiles(true);
                                                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>"+obj1.data+"</span></div></div>");
                                            }
                             }'),
                        'error' => new \yii\web\JsExpression('function(){
                                    var appendId = "' . $appendId . '";
                                    $("#pageloader").hide();
                                    $("#loadercontent").hide();
                                    if($("#file_name' . $appendId . '").val()==""){
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-times\'></i></div><span>Please select file.</span></div></div>");
                                            // message: "<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-exclamation\'></i><span>Please select file.</span></div></div></div>",
                                    }else{
                                        $("#importModal' . $appendId . '").modal("toggle");
                                        $("#import-form' . $appendId . '")[0].reset();
                                        Dropzone.forElement("#mainDrop' . $appendId . '").removeAllFiles(true);
                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>You have error in your file</span></div></div>");
                                    }
                             }'),
                    ],
                    'options' => ['class' => 'btn btn-primary',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = "
            Dropzone.autoDiscover = false;
            var appendId = '" . $appendId . "';
            $('.import-file" . $appendId . "').on('click',function(e){
                    $('#importModal" . $appendId . "').modal('toggle');
                    var flg = $(this).attr('data-map-flag');
                    
                    var ref = $('#importModal" . $appendId . " #sample_download a').attr('href');
                    if(flg == 1){
                        ref = ref+'&mapping='+flg;
                    } else {
                        if(ref.indexOf('&mapping=1') >= 0){
                            ref = ref.replace('&mapping=1', '');
                        }
                    }
                    $('#importModal" . $appendId . " #sample_download a').attr('href',ref);

                    $('#mappingField" . $appendId . "').val(flg);
                    var type = '" . $type . "';
                    $.ajax({
                            type: 'post',
                            url: '" . Url::to(['/import/default/get-fields']) . "',
                            data: 'sel='+flg+'&type='+type,
                            success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('.fields').html(obj1);
                            },
                            error:function(data){
                                        //alert('Your data has not been submitted..Please try again');
                                    }
                    });
            });
           $('.close-import').on('click',function(e){
                Dropzone.forElement('#mainDrop" . $appendId . "').removeAllFiles(true);
            });

            $('#mapping').on('change',function(e){
                var sel = $(this).val();
                var type = '" . $type . "';
                $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/import/default/get-fields']) . "',
                        data: 'sel='+sel+'&type='+type,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            $('.fields').html(obj1);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
                });
            });
            
//            $('#tblmember-dcs_code').on('change',function(e){
//                var code = $(this).val();
//                var name = $('#tblmember-dcs_code option:selected').text();
//                var type = '" . $type . "';
//                $.ajax({
//                        type: 'post',
//                        url: '" . Yii::$app->request->baseUrl . "/dcsoperation/tbl-member/dcs-member-exist',
//                        data: 'code='+code,
//                        success: function(data) {
//                            var cnt = $.parseJSON(data);
//                            if(cnt.cnt>0)
//                            {
//                               bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-danger\"><i class=\"fa fa-times\"></i></div><span>Society '+name+' already has members.</span></div></div>'); 
//                               $('#importModal').modal('toggle');
//                               $('#tblmember-dcs_code').val('');
//                            }
//                        },
//                        error:function(data){
//                                    //alert('Your data has not been submitted..Please try again');
//                                }
//                });
//            });
";
$this->registerJs($script, View::POS_END, 'import-manager' . $appendId);
?>