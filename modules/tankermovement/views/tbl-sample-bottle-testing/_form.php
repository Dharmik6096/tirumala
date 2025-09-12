<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?php echo Html::hiddenInput('trip_status', 'open###tankerfull', ['id' => 'tblsamplebottletesting-trip_status']); ?>
        <?= Yii::$app->dropdown->depend_dropdown('union_trip', $model, $form, 'tblsamplebottletesting-union_code,tblsamplebottletesting-trip_status', 'form-group col-sm-4', $model->getAttributeLabel('trip_code'), '', FALSE); ?>
    </div>

    <div class="col-sm-2 number-validate"> 
        <?= $form->field($model, 'sample_no')->textInput() ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', true, true, 'milk_type_code'); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', true, true, 'milk_quality_type_code'); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'sample_bottle_testing_date', '', TRUE, FALSE); ?>
    </div>
    <div class="col-sm-2 number-validate"> 
        <?= $form->field($model, 'fat')->textInput(['class' => 'two-decimal-validate']) ?>
    </div>
    <div class="col-sm-2 number-validate"> 
        <?= $form->field($model, 'snf')->textInput(['class' => 'two-decimal-validate']) ?>
    </div>
    <div class="col-sm-2 number-validate"> 
        <?= $form->field($model, 'protein')->textInput() ?>
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
    $(document).on('blur','#tblsamplebottletesting-sample_no', function() {
        var trip_code = $('#tblsamplebottletesting-trip_code').val();
        var sample_no = $('#tblsamplebottletesting-sample_no').val();
          if(trip_code != '' && sample_no !=''){
            $('#tblsamplebottletesting-milk_type_code').val('');
            $('#tblsamplebottletesting-milk_quality_type_code').val('');             
             $.ajax({
                type: 'get',
                url: '" . Url::to(['check-sample-no']) . "',
                data: {'trip_code' : trip_code,'sample_no':sample_no},             
                beforeSend: function() {  
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },                
                success: function(data) {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();                  
                    var data=$.parseJSON(data);                 
                  if (data.status == 'success'){   
                    $('#tblsamplebottletesting-milk_type_code').val(data.milk_type_code);
                    $('#tblsamplebottletesting-milk_quality_type_code').val(data.milk_quality_type_code);
                }else {
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>' + data.msg + '</span></div></div>');
                }
               },
               error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                } 
            });   

        }      
    });
";
$this->registerJs($script, View::POS_END, 'validate-sample-bottle');
?>