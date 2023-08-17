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
    <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tbldcsprovisional-bank_code', '', 'Branch', 'branch_code'); ?>                        
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
<?= $form->field($model, 'is_default', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
</div>-->
<?php
$script = "
   $('#tbldcsprovisional-bank_code').on('change',function(){
        $('#tbldcsprovisional-ifsc').val('');
    });
    
    $('#tbldcsprovisional-branch_code').on('change',function(){
            var id = $('#tbldcsprovisional-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tbldcsprovisional-ifsc').val(obj1.code);
//                                if(obj1.code!='')
//                                    $('#tbldcsprovisional-ifsc').prop('readonly', true);
//                                else
//                                    $('#tbldcsprovisional-ifsc').prop('readonly', true);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'bank-select');
