<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php
$infourl = Url::to(['/verification/verification/kyc-information']);
$form = ActiveForm::begin(['options' => [
                'class' => 'popup-form',
                'id' => 'kyc-form',
            ], 'validateOnBlur' => TRUE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => TRUE,
            'validateOnSubmit' => TRUE,
            'action' => Url::to(['/verification/verification/kyc-detail'])
        ]);
?>
<div class="modal modal-default fade" id="KYCModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'KYC Details'); ?></h4>
            </div>
            <div class="modal-body">            
                <div class="panel-heading" id="header"><?php echo Yii::t('app', 'KYC Details'); ?></div>
                <div class="panel-body">
                    <div class="panel-subheading">
                        <?= Html::activeHiddenInput($model, 'module_id'); ?>
                        <?= Html::activeHiddenInput($model, 'module_name'); ?>
                        <div class="col-sm-6">
                            <label>Address Detail</label>
                            <div id="kyc-address" class="kyc-box">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Bank Detail</label>
                            <div id="kyc-bankdata" class="kyc-box">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>PAN No.</label>
                            <?= Html::textInput('kyc-pan', '', ['disabled' => TRUE, 'id' => 'kyc-pan', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-sm-6">
                            <label>Aadhar No.</label>
                            <?= Html::textInput('kyc-aadhar', '', ['disabled' => TRUE, 'id' => 'kyc-aadhar', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'kyc_doc1')->radioList($model->getAddressProof()) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'kyc_doc2')->radioList($model->getBankProof()) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'kyc_remark')->textInput() ?>
                        </div>
                    </div>       
                </div>
                <div class="footer">
                    <button type="button" class="btn btn-default btn-raised close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-default']); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
 $('#" . $id . "').on('click','.kyc-modal',function(e){  
           $('#kyc-form')[0].reset();
            var name = $(this).data('name');
            var code = $(this).data('val');           
        $.ajax({
        type:'POST', 
        url: '{$infourl}',
        data: {'flag':name,'id':code},
        success: function(response) {
         var data=$.parseJSON(response);
        if(data.status=='success'){
             $('#header').text('Add KYC Details of ' +data.info['name']);
             $('#tblkycrecord-module_id').val(code);
             $('#tblkycrecord-module_name').val(name);
             $('#kyc-address').html(data.info['address']); 
             $('#kyc-bankdata').html(data.info['bankdetail']);
             $('#kyc-pan').val(data.info['panno']); 
             $('#kyc-aadhar').val(data.info['aadharno']); 
             $('#KYCModal').modal('toggle');
             }
        }
    });    
         

            });               

";
Yii::$app->view->registerJs($script, View::POS_END, 'kyc-detail-script');
?>