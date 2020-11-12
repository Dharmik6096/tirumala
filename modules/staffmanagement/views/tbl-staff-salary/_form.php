<?php

use yii\bootstrap\ActiveForm;
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
    <?= Html::activeHiddenInput($model, 'staff_salary_code'); ?> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffsalary-union_code', 'form-group col-sm-2 ' . $class, Yii::t('app', 'Staff Member'), 'staff_member_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?php
        $model->wef_date = !empty($model->wef_date) ? date('m-Y', strtotime($model->wef_date)) : NULL;
        ?>
        <?=
        $form->field($model, 'wef_date')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control ' . $class, 'readonly' => $readonly],
            'mask' => '99-9999',])
        ?> 
    </div>
    <?= $form->field($model, 'total_value', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['readOnly' => TRUE]) ?>

</div>
<div class="row staffMemberArea"></div>

<?php ActiveForm::end(); ?>

<?php
$script = "
        var specialDecimalKeys = new Array();
        specialDecimalKeys.push(8);
        $(document).on('keypress', '.number-validate', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
        });

        $(document).on('change', '#tblstaffsalary-staff_member_code', function() { 
            getData();
        });
        $(document).on('change', '#tblstaffsalary-wef_date', function() { 
            getData();
        });
    
        function getData(){
                var id = $('#tblstaffsalary-staff_salary_code').val();
                var code = $('#tblstaffsalary-staff_member_code').val();
                var date = $('#tblstaffsalary-wef_date').val();
                if(code !='' && date!=''){
                    $.ajax({
                    type: 'get',
                    url: '" . Url::to(['salary-transaction']) . "',    
                    data: {'staff_member_code' : code,'wef_date': date,'id': id},
                       success: function(data) {
                         $('.staffMemberArea').html(data);
                       }
                    });   
                } else {
                    $('.staffMemberArea').empty();
                }
        }
        
        $(document).on('change','.addition', function() { 
        var add = 0;
        $('.addition input').each(function(){
            var val = $(this).val();
            if(isNaN(val) || val == null || val == undefined || val == ''){
                    val = 0;
            }
            add = add + parseFloat(val);
        });
          $('#tblstaffsalary-addition').val(add);
            totalAmount();
        });
        
        $(document).on('change','.deduction', function() { 
        var ded = 0;
        $('.deduction input').each(function(){
            var val = $(this).val();
            if(isNaN(val) || val == null || val == undefined || val == ''){
                    val = 0;
            }
            ded = ded + parseFloat(val);
        });
            $('#tblstaffsalary-deduction').val(ded);
              totalAmount();
        });
        
        function totalAmount(){
            var add = parseFloat($('#tblstaffsalary-addition').val());
            if(isNaN(add)){
               add = 0;
            }
            var deduct = parseFloat($('#tblstaffsalary-deduction').val());
            if(isNaN(deduct)){
                deduct = 0;
            }
            var totalAmount = 0;          
            if(add != '' && deduct =='') {
                totalAmount = add;
            } else if(deduct != '' && add =='') {
                totalAmount = deduct;
            } else if(add != '' && deduct !='') {
                totalAmount = add - deduct;
            }
            $('#tblstaffsalary-total_value').val(totalAmount);
        }

        $(document).on('ready', function(){
            setTimeout(function(){
                totalAmount();
            }, 1000);
            getData();
        });

";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>