<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'no_pointer';
$defaultToggle = true;
?>

<div class="modal modal-default fade" id="configSetModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Search Vendor Payment Config'); ?></h4>
            </div>
            <div class="">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'userUpdatePsd',
                            'options' => [],
                            'validateOnBlur' => FALSE,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>
                <?php // echo $form->errorSummary($model); ?>
                <div class="row margin_0">
                    <div class="modal-body">
                        <div class="col-sm-6">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
                        </div>
                        <div class="col-sm-4 "style="display: none">
                            <?= Yii::$app->dropdown->configFor($model, $form, 'config_for', $model->getAttributeLabel('config_for'), $readonly); ?>  
                        </div>
                        <div class="col-sm-4">
                            <?= Html::hiddenInput('input', 1, ['id' => 'input']); ?>
                            <?= Yii::$app->dropdown->processName($model, $form, 'tblconfig-config_for,input', 'process_name', $model->getAttributeLabel('process_name')); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblconfig-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblconfig-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-4 bmc_class">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblconfig-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
                        </div>

                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <?= Yii::$app->controls->search(); ?>
                        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>    

<div id='transaction_view'>

</div>
<?php
$script = "
    $('form#userUpdatePsd').submit(function(e) {
        e.preventDefault();
        ViewTransaction();
    });
    
    $('.mis_report_modal_toggle').on('click', function(){
        $('#configSetModal').modal('toggle');
    });
   
     function ViewTransaction(){
     var confor = $('#tblconfig-config_for').val();
     var union_code = $('#tblconfig-union_code').val();
     var plant = $('#tblconfig-plant_code').val();
     var mcc = $('#tblconfig-mcc_plant_code').val();
     var bmc = $('#tblconfig-bmc_code').val();
     var process = $('#tblconfig-process_name').val();
        if(confor != '' && union_code!='' && plant !='' && mcc !='' && bmc !='' && process !=''){  
         $('#transaction_view').show(); 
        $.ajax({
                type: 'get',
                url: '" . Url::to(['payment-config-data']) . "',
                data: {'config_for' : confor,'union_code':union_code,'plant':plant,'mcc':mcc,'bmc':bmc,'process':process},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#transaction_view').html(data);
                   $('#loadercontent').hide();
                   $('#pageloader').hide(); 
                   $('#configSetModal').modal('hide');
//                   $('#configSetModal').modal('toggle');
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                   $('#configSetModal').modal('hide');
//                    $('#configSetModal').modal('toggle');
                }
            });
        }else{
        $('#transaction_view').hide(); 
        }
    }
    $('#configSetModal').modal('toggle');
";

$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>