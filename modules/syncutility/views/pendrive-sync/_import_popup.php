<?php

use zainiafzan\widget\Dropzone;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal modal-default fade" id="excelImport" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Import File'); ?></h4>
            </div>


            <div class="modal-body">              
                <?php
                $form = ActiveForm::begin(['options' => [
                                'class' => 'popup-form',
                                'id' => 'import-excel-form',
                                'url' => ['import'],
                            ], 'fieldConfig' => [
                ]]);
                ?>
                <div class="row">                    
                    <?=
                    Dropzone::widget([
                        'id' => 'mainDrop',
                        'options' => [
                            'acceptedMimeTypes' => ".7z",
                            'url' => Url::to(['/syncutility/pendrive-sync/save-zip']),
                            'addRemoveLinks' => true,
                            'autoDiscover' => false,
                            'maxFiles' => 1,
                        ],
                        'clientEvents' => [
                            'success' => "function( file, response ){
                                            var data=$.parseJSON(response);                                           
                                            if(data.status=='success'){
                                                this.removeFile(file);                                                                     
                                                $('#excelImport').modal('toggle');
                                                $.ajax({
                                                //  url: 'import?file='+data.msg,
                                                  url: '" . Url::to(['import']) . "'+'?file='+data.msg,
                                                  type: 'POST',
                                                //  data:  {'file':data.msg},
                                                  cache : false,
                                                  processData: false,
                                                  beforeSend:function (data){
                                                  $('#loadercontent').show();
                                                     $('#pageloader').show();
                                                  },
                                                  success:function (data) {
                                                  var data=$.parseJSON(data);  
                                                    $('#loadercontent').hide();
                                                    $('#pageloader').hide();
                                                   bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-info-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+data.msg+'</div></div>');
                                                  }
                                                  });                                          
                                            }else{
                                              bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-info-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'>'+data.msg+'</div></div>');
                                           }
                                        }",
                            'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
                        ]
                    ]);
                    ?>
                </div>

            </div>
            <div class="modal-footer">              
                <button type="button" class="btn btn-default btn-raised close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>