<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$class = $type == 'edit' ? 'disabled' : '';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'staff-designation-form'],
            'validateOnBlur' => FALSE,
            
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
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffmemberdesignation-union_code', 'form-group  ' . $class, $model->getAttributeLabel('staff_member_code'), 'staff_member_code', TRUE); ?>
    </div>

    <?= Html::activeHiddenInput($model, 'staff_member_designation_code', ['value' => $model->staff_member_designation_code]); ?>  
    <div class="col-sm-2 create_fields">
        <?= Yii::$app->dropdown->dropdown('designation_code', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('designation_code')); ?>
    </div>
    <div class="col-sm-2 create_fields">
        <?= Yii::$app->controls->date($model, $form, 'tenure_from_date', 'form-group col-sm-2', FALSE); ?>
    </div> 
    <div class="col-sm-2 create_fields">
        <?= Yii::$app->controls->date($model, $form, 'tenure_to_date', 'form-group col-sm-2', FALSE); ?>
    </div>
    <?= $form->field($model, 'remark', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Add'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['staff-member-designation', 'id' => $model->staff_member_code]),
                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
//                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    window.location = data.url;
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                }else{
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
        </div>

    </div>
</div>

<?php ActiveForm::end(); ?>
<?php
$script = " 
// for Selected Data Of Staff Designation On click Update
function editDesignation(staffMemberDesignationCode) {
    $('tr').removeClass('RemoveOnEdit');
    $('tr.'+staffMemberDesignationCode).addClass('RemoveOnEdit');
    var staff_member_designation_code = $('#tblstaffmemberdesignation-'+staffMemberDesignationCode+'-staff_member_designation_code').val();
    $('#tblstaffmemberdesignation-staff_member_designation_code').val(staff_member_designation_code);
    
    var tenure_to_date = $('#tblstaffmemberdesignation-'+staffMemberDesignationCode+'-tenure_to_date').val();
    $('#tblstaffmemberdesignation-tenure_to_date').val(tenure_to_date);
    
    var tenure_from_date = $('#tblstaffmemberdesignation-'+staffMemberDesignationCode+'-tenure_from_date').val();
    $('#tblstaffmemberdesignation-tenure_from_date').val(tenure_from_date);
    
    var designation_code = $('#tblstaffmemberdesignation-'+staffMemberDesignationCode+'-designation_code').val();
    $('#tblstaffmemberdesignation-designation_code').val(designation_code);
    
    var remark = $('#tblstaffmemberdesignation-'+staffMemberDesignationCode+'-remark').val();
    $('#tblstaffmemberdesignation-remark').val(remark);
}
//$(document).ready(function () {
//    $('#tblmomaction-date').blur();
//});

";
$this->registerJs($script, View::POS_END, 'staff-member-designation');
?>