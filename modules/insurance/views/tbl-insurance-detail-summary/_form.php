<?php

use app\components\ActiveForm; 
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('insurance_master_list', $model, $form, 'form-group col-sm-2 padding-right-5', $model->getAttributeLabel('insurance_master_code'), $readonly); ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblinsurancedetailsummary-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblinsurancedetailsummary-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinsurancedetailsummary-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->insurance_dcs($model, $form, 'tblinsurancedetailsummary-insurance_master_code,tblinsurancedetailsummary-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE, '', TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2 mt10 hide">
        <?= $form->field($model, 'is_revoke', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-12 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = "
    $(document).on('change', '#tblinsurancedetailsummary-dcs_code', function() { 
            $('#tblinsurancedetailsummary-to_date').val(''); 
            $('#tblinsurancedetailsummary-from_date').val(''); 
            setFromToDate();
        });
    
    function setFromToDate(){
        var dcs_code = $('#tblinsurancedetailsummary-dcs_code').val();
        var insurance_master_code = $('#tblinsurancedetailsummary-insurance_master_code').val();
        if(dcs_code != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-from-to-date']) . "',
                data: {'dcs_code':dcs_code,'insurance_master_code':insurance_master_code},
                success: function(data) {   
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('.field-tblinsurancedetailsummary-is_revoke').parent().addClass('hide');
                        if(obj.data_status == 'PARTIAL_FINALIZE'){
                            $('.field-tblinsurancedetailsummary-is_revoke').parent().removeClass('hide');
                        }
                        $('#tblinsurancedetailsummary-from_date').kvDatepicker({
                            format: 'dd-mm-yyyy',
                            autoclose: true
                        }).kvDatepicker('update', obj.from_date);
                        $('#tblinsurancedetailsummary-to_date').kvDatepicker({
                            format: 'dd-mm-yyyy',
                            autoclose: true
                        }).kvDatepicker('update', obj.to_date);
                    } else {
                        $('#tblinsurancedetailsummary-from_date').val(''); 
                        $('#tblinsurancedetailsummary-to_date').val(''); 
                    }
                },
                error:function(data){
                    $('#tblinsurancedetailsummary-from_date').val('');
                    $('#tblinsurancedetailsummary-to_date').val('');
                }
            });
        } else{
             $('#tblinsurancedetailsummary-to_date').val(''); 
             $('#tblinsurancedetailsummary-from_date').val(''); 
        }
    }
    
    ";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
