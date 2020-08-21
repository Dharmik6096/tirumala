<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'no_pointer';
?>

<?php
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
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->configFor($model, $form, 'config_for', $model->getAttributeLabel('config_for'), $readonly); ?>  
    </div>

</div>
<?php ActiveForm::end(); ?>
<div id='transaction_view'>

</div>
<?php
$script = "
    $(document).on('change','#tblconfig-config_for', function() {
        ViewTransaction();
    });
    $(document).on('change','#tblconfig-union_code', function() {
        ViewTransaction();
    });
   
     function ViewTransaction(){
     var confor = $('#tblconfig-config_for').val();
     var union_code = $('#tblconfig-union_code').val();
        if(confor != '' && union_code!=''){  
         $('#transaction_view').show(); 
        $.ajax({
                type: 'get',
                url: '" . Url::to(['get-master-data']) . "',
                data: {'config_for' : confor,'union_code':union_code},
                beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
                },
                success: function(data) {
                  $('#transaction_view').html(data);
                   $('#loadercontent').hide();
                   $('#pageloader').hide();                                                                  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        }else{
        $('#transaction_view').hide(); 
        }
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>