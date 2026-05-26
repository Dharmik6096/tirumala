<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\helpers\Html;

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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblinsurancedetail-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblinsurancedetail-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblinsurancedetail-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblinsurancedetail-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, '', $readonly); ?>         
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblinsurancedetail-dcs_code', '', $model->getAttributeLabel('member_code'), 'member_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Html::activeHiddenInput($model, 'member_id'); ?>
        <?= Html::hiddenInput('type', $type, ['id' => 'type']); ?>
        <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>
    </div> 
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'adhar_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'dob'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_of_joining_scheme', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('gender', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('gender')); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'nominee_member_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length', 'maxlength' => 10, 'placeholder' => 'Enter 10-digit mobile No']) ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'nominee_adhar_no')->textInput(['maxlength' => true]) ?>
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
$(document).on('change', '#tblinsurancedetail-member_code', function() { 
        $('#tblinsurancedetail-member_name').val(''); 
        setMemberName();
    });
    
    function setMemberName(){
        var member_code = $('#tblinsurancedetail-member_code').val();
        var member_id = $('#tblinsurancedetail-member_id').val();
        var type = $('#type').val();
        if(member_code != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-member-name']) . "',
                data: {member_code: member_code, type: type, member_id: member_id},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success') {
                        $('#tblinsurancedetail-member_name').val(obj.member_name);
                      }
                      else {
                        $('#tblinsurancedetail-member_name').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblinsurancedetail-member_name').val(''); 
        }
    }
    
    ";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>