<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblAssetDepreciationCompanyAct */
/* @var $form yii\widgets\ActiveForm */

$title = Yii::$app->label->title($type, 'Installment Detail');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'edit' ? true : false;
$class = $type == 'edit' ? 'disabled' : '';
?>


<?php
$form = ActiveForm::begin(['options' => [
                'field-class' => 'form-group col-sm-3',
            ], 'validateOnBlur' => FALSE,
            'validateOnEnter' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<div class="panel-subheading">
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?= Html::activeHiddenInput($model, 'staff_addition_deduction_no'); ?> 
        <?= Html::activeHiddenInput($model, 'installment_no'); ?> 

        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffadditiondeduction-union_code', 'form-group  ' . $class, Yii::t('app', 'Staff Member'), 'staff_member_code', $readonly); ?>
        </div>

        <?php if ($type == 'create') { ?>
            <?php $model->tr_date = empty($model->tr_date) ? date('Y-m-d') : NULL; ?>
        <?php } ?>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'tr_date', 'form-group col-sm-2 ' . $class, true, '', false); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('type', $model, $form, 'form-group ' . $class, $model->getAttributeLabel('type')); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'amount', ['options' => ['class' => 'form-group']])->textInput(['readOnly' => TRUE]) ?>
        </div>
        <div class="salaryInstall">
            <div class="col-sm-2">
                <?php
                $staffModel->deduction_date = !empty($model->app_from_date) ? date('m-Y', strtotime($model->app_from_date)) : NULL;
                ?>
                <?=
                $form->field($staffModel, 'deduction_date')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                    'mask' => '99-9999',])->label('Month App From')
                ?> 
            </div> 
            <?= Html::activeHiddenInput($staffModel, 'staff_installment_code'); ?> 
            <?= $form->field($staffModel, 'amount', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="panel-footer col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?php
        $url = ($type == 'create') ? ['salary-installment'] : ['salary-installment'];
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Add'),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to($url),
                'beforeSend' => new JsExpression("function(data){ 
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    }"),
                'success' => new JsExpression('function(data){ 
                                if (data.status == "error") {
                                    var error_content = "";
                                    $(".help-block").parent("div").removeClass("has-error");
                                    $(".help-block").html("");
                                    $(\'#loadercontent\').hide();
                                    $(\'#pageloader\').hide(); 
                                    $.each(data.error_data, function(key, val) {
                                        error_content += val;
                                        var parent_div = $("#"+key).parent("div");
                                        parent_div.find(".help-block").remove();
                                        $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                        $("#"+key).closest(".form-group").addClass("has-error");                                          
                                    }); 
                                    $("#tblstaffadditiondeduction-installment_no").val("");
                                } else {
                                    $(".appendRaw").append(data);
                                    $(".salaryInstall select").val("");
                                    $(".salaryInstall input").val("");
                                    $("#loadercontent").hide();
                                    $("#pageloader").hide();
                
                                 
                                    $("tr.RemoveOnEdit").remove();
                                    $(".panel-footer").removeClass("disbledAndroid");
                                    $("#tblstaffadditiondeduction-installment_no").val(""); 
                                    setInstallmentNo();
                                }
                     }'),
            ],
            'options' => ['class' => 'btn btn-primary web_form_btn mr10',
                'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = " 

    $(document).on('ready', function(){
        $('#tblstaffadditiondeduction-installment_no').val('');  
        setInstallmentNo();
    });
    
    function setInstallmentNo(){
        var int = $('#tblstaffadditiondeduction-installment_no').val();
        var inst_code = $('#tblstaffinstallment-staff_installment_code').val();
        if(inst_code=='' && int==''){
            var count = $('table tbody tr').length;
            count = count + 1;
            $('#tblstaffadditiondeduction-installment_no').val(count);
        }
    }

// for Selected Data Of Installation On click Update
function editSalaryInstall(installmentNo) {
  $('#tblstaffadditiondeduction-installment_no').val(installmentNo);
    $('tr').removeClass('RemoveOnEdit');
    $('tr.'+installmentNo).addClass('RemoveOnEdit');
    var staff_addition_deduction_no = $('#tblstaffinstallment-'+installmentNo+'-staff_addition_deduction_no').val();
    $('#tblstaffadditiondeduction-staff_addition_deduction_no').val(staff_addition_deduction_no);
    
    var staff_installment_code = $('#tblstaffinstallment-'+installmentNo+'-staff_installment_code').val();
    $('#tblstaffinstallment-staff_installment_code').val(staff_installment_code);
   
    var amount = $('#tblstaffinstallment-'+installmentNo+'-amount').val();
    $('#tblstaffinstallment-amount').val(amount);
    
     var app_from_date = $('#tblstaffinstallment-'+installmentNo+'-deduction_date').val();
    $('#tblstaffinstallment-deduction_date').val(app_from_date);

}

";
$this->registerJs($script, View::POS_END, 'salary-install');
?>