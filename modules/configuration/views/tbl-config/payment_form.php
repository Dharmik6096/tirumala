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
                <h4 class="modal-title"><?php echo Yii::t('app', 'Search Payment Configurations'); ?></h4>
            </div>
            <div class="">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'userUpdatePsd',
                            'options' => [],
                            'validateOnBlur' => FALSE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>
                <?php // echo $form->errorSummary($model); ?>
                <div class="row margin_0">
                    <div class="modal-body">
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
                        </div>
                        <div class="col-sm-3 "style="display: none">
                            <?= Yii::$app->dropdown->configFor($model, $form, 'config_for', $model->getAttributeLabel('config_for'), $readonly); ?>  
                        </div>
                        <div class="col-sm-3">
                            <?= Html::hiddenInput('input', 1, ['id' => 'input']); ?>
                            <?= Html::hiddenInput('config_param', $config_param, ['id' => 'config_param']); ?>
                            <?= Yii::$app->dropdown->processName($model, $form, 'config_param,input', 'process_name', $model->getAttributeLabel('process_name')); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblconfig-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-3 mcc_error plant_hide">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblconfig-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-3 bmc_class bmc_error plant_hide">
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
        var union_code = $('#tblconfig-union_code').val();
        var plant = $('#tblconfig-plant_code').val();
        var mcc = $('#tblconfig-mcc_plant_code').val();
        var bmc = $('#tblconfig-bmc_code').val();
        var processName = $('#tblconfig-process_name').val();
        var process = '';
        var configFor = '';
        if (processName && processName.includes('##')) {
            var process_config = processName.split('##');
            process = process_config[0];
            configFor = process_config[1];
        } 
        $('.mcc_error').find('.help-block').remove();
        $('.bmc_error').find('.help-block').remove();
        
        if (configFor == 'BMC') {
            if (mcc == '' || bmc == '') {
                if (mcc == '') {
                    $('.mcc_error').append('<div class=\"help-block\">MCC Plant Code cannot be blank.</div>');
                }
                if (bmc == '') {
                    $('.bmc_error').append('<div class=\"help-block\">BMC Code cannot be blank.</div>');
                }
                return; 
            }
        }
        if(union_code!='' && plant !='' && process !=''){  
         $('#transaction_view').show(); 
        $.ajax({
                type: 'get',
                url: '" . Url::to(['payment-config-data']) . "',
                data: {'config_for' : configFor,'union_code':union_code,'plant':plant,'mcc':mcc,'bmc':bmc,'process':process},
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
    

    $(document).ready(function() {
    
    $('#tblconfig-process_name').on('change',function(){
        var process_name = $(this).val();
        var configFor = process_name.split('##')[1];
        hideHierarchy(configFor);
    });
    
    var initialProcessName = $('#tblconfig-process_name').val();
    var initialConfigFor = initialProcessName.split('##')[1];
    hideHierarchy(initialConfigFor);
        
    function hideHierarchy(configFor) {
        $('.plant_hide').hide();
        if (configFor == 'PLANT') {
            $('.plant_hide').hide();
            $('#tblconfig-mcc_plant_code').val('');
            $('#tblconfig-bmc_code').val('');
        } else {
            $('.plant_hide').show();
        }
    }  
    });
";

$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>