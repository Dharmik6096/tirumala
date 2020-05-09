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
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?= Html::activeHiddenInput($model, 'staff_salary_code'); ?> 
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffsalary-union_code', 'form-group col-sm-3 ' . $class, Yii::t('app', 'Staff Member'), 'staff_member_code', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php
        $model->wef_date = !empty($model->wef_date) ? date('m-Y', strtotime($model->wef_date)) : NULL;
        ?>
        <?=
        $form->field($model, 'wef_date')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control ' . $class, 'readonly' => $readonly],
            'mask' => '99-9999',])
        ?> 
    </div>
    <?= $form->field($model, 'total_value', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['readOnly' => TRUE]) ?>

</div>
<div class="row staffMemberArea"></div>

<?php ActiveForm::end(); ?>

<?php
$script = "
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
        $(document).on('change','#tblstaffsalarytransaction-1-value', function() {
        $('#tblstaffsalary-addition').val('');
        $('#tblstaffsalary-total_value').val('');
            addAmount();
            totalAmount();
        });
        $(document).on('change','#tblstaffsalarytransaction-2-value', function() {
        $('#tblstaffsalary-addition').val('');
        $('#tblstaffsalary-total_value').val('');
           addAmount();
           totalAmount();
        });
        
        function addAmount(){
            var val_1 = parseFloat($('#tblstaffsalarytransaction-1-value').val());
            if(isNaN(val_1)){
               val_1 = 0;
            }
            var val_2 = parseFloat($('#tblstaffsalarytransaction-2-value').val());
            if(isNaN(val_2)){
                val_2 = 0;
            }
            var addition = 0;          
            if(val_1 != '' && val_2 ==''){
                addition = val_1;
               $('#tblstaffsalary-addition').val(addition);
            }else if(val_2 != '' && val_1 ==''){
                addition = val_2;
                $('#tblstaffsalary-addition').val(addition);
            }else if(val_1 != '' && val_2 !=''){
                 addition = val_1 + val_2;
                 $('#tblstaffsalary-addition').val(addition);
            }

        }
        $(document).on('change','#tblstaffsalarytransaction-3-value', function() {
            $('#tblstaffsalary-deduction').val('');
            $('#tblstaffsalary-total_value').val('');
            addDeduct();
            totalAmount();
        });
        
        function addDeduct(){
            var val_3 = parseFloat($('#tblstaffsalarytransaction-3-value').val());
            var deduction=0;          
            if(val_3 != ''){
                deduction = val_3;
               $('#tblstaffsalary-deduction').val(deduction);
            }

        }
        
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