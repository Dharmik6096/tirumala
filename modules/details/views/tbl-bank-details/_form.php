<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php // Yii::$app->warning->hiddenfields($nameWarning, '');          ?>
<?php if (!empty($dist)) { ?>
    <?= Html::hiddenInput('union-dist', $dist, ['id' => $dist_field]) ?>
<?php } ?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->bankdepended($model, $form, $dist_field, 'bank_code', 'Bank'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblbankdetails-bank_code', '', 'Branch', 'branch_code'); ?>                        
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'bank_account_no')->textInput() ?>
</div>
<div class="col-sm-2">
    <!--<? = $form->field($model, 'ifsc')->textInput(['maxlength' => true, 'readonly' => !empty($model->ifsc) ? true : false]) ?>-->
    <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true, 'readonly' => true]) ?>    
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'beneficiary_name')->textInput() ?>
</div>
<!--<div class="col-sm-2">
<? $form->field($model, 'is_default', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
</div>-->
<?php
$script = "
   $('#tblbankdetails-bank_code').on('change',function(){
        $('#tblbankdetails-ifsc').val('');
    });
    
    $('#tblbankdetails-branch_code').on('change',function(){
            var id = $('#tblbankdetails-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblbankdetails-ifsc').val(obj1.code);
//                                if(obj1.code!='')
//                                    $('#tblbankdetails-ifsc').prop('readonly', true);
//                                else
//                                    $('#tblbankdetails-ifsc').prop('readonly', true);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'bank-select');
