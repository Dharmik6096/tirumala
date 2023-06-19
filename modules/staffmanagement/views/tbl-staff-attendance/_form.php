<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? true : false;

$form = ActiveForm::begin([
            'options' => ['id' => 'staff-attendance-form'],
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffattendance-union_code', 'form-group col-sm-2 ' . $class, $model->getAttributeLabel('staff_member_code'), 'staff_member_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->leaveType($model, $form, 'tblstaffattendance-union_code,tblstaffattendance-staff_member_code', 'leave_type', $model->getAttributeLabel('leave_type')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('lwp_type', $model, $form, 'form-group', $model->getAttributeLabel('lwp_type')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'leave_from', 'form-group col-sm-2', false, '', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'leave_to', 'form-group col-sm-2', false, '', false); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'remark', ['options' => ['class' => 'form-group']])->textarea() ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
   dateSelection();
    $('#tblstaffattendance-lwp_type').on('change',function(){
      dateSelection();
    });
    $('#tblstaffattendance-leave_from').on('change',function(){
      dateSelection();
    });
    function dateSelection(){
        var type = $('#tblstaffattendance-lwp_type').val();
        var from = $('#tblstaffattendance-leave_from').val();
        if(from != '' && type != '' && type=='0'){
            $('#tblstaffattendance-leave_to').val(from);
            $('#tblstaffattendance-leave_to').prop('disabled', true); 
        }else{
            $('#tblstaffattendance-leave_to').prop('disabled', false); 
        }
    } 
";
$this->registerJs($script, View::POS_END, 'panel-attendance-form');
?>
