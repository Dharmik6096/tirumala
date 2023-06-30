<?php

use kato\DropZone;
use yii\widgets\ActiveForm;
?>
<div class="modal modal-default fade" id="excelImport" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Import File'); ?></h4>
            </div>


            <div class="modal-body">              
                <?php
                $form = ActiveForm::begin(['options' => [
                                'class' => 'popup-form',
                                'id' => 'import-excel-form',
                            ], 'fieldConfig' => [
                ]]);
                ?>
                <div class="row">                    
                    <?=
                    Dropzone::widget([
                        'id' => 'mainDrop',
                        'options' => [
                            'acceptedMimeTypes' => ".xls,.xlsx",
                            'url' => \yii\helpers\Url::to(['/import/default/import-rate-excel',
                                'main' => 1,]),
                            'addRemoveLinks' => true,
                            'autoDiscover' => false,
                            'maxFiles' => 1,
                        ],
                        'clientEvents' => [
                            'success' => "function( file, response ){
                                            var data=$.parseJSON(response);
                                            
                                            if(data.status=='success'){
                                                this.removeFile(file);
                                                $('#file_name').val(data.msg);
                                                $('#excelImport').modal('toggle');
                                                $('#purchase-rate-form').submit();
                                                
                                            }
                                            else
                                                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');                                              
                                        }",
                            'sending' => "function(file, xhr, formData){formData.append('" . Yii::$app->request->csrfParam . "','" . Yii::$app->request->getCsrfToken() . "')}"
                        ]
                    ]);
                    ?>
                </div>

            </div>
            <div class="modal-footer">              
                <button type="button" class="btn btn-default btn-raised close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>