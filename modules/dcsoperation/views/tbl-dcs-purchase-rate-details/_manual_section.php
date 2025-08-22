<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'Milk Rate - Manually');
//$button = Yii::$app->label->button($type);
?>
<?php
$form = ActiveForm::begin(['id' => 'dynamic-form',
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>

<div class="row panel-subheading padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
        <h4 class="theme-box-heading"><?php echo Yii::t('app', $this->title); ?></h4>
    </div>

    <?php
    echo $form->errorSummary($purchaseBasedModel);
    $qualityparam = $purchaseBasedModel->quality_param_code;
    ?>


        <div class="col-sm-2 change">
            <?= Yii::$app->dropdown->dropdown('rate_type_code', $purchaseBasedModel, $form, '', 'Rate Type', false, '[0]rate_type'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $purchaseBasedModel, $form, '', 'Milk Quality Type', false, '[0]milk_quality_type_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel, $form, '', 'Milk Type', false, '[0]milk_type_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]quality_param_code')->dropDownList([], ['prompt' => Yii::t('app','Select Quality Param')]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]start_range')->textInput(['class' => 'form-control number-validate'])->label('Start') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]end_range')->textInput(['class' => 'form-control number-validate'])->label('End') ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]kg_rate')->textInput(['class' => 'form-control number-validate']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]deduction_type')->dropDownList([0 => 'NA', 1 => 'Value Addition', 2 => 'Value Deduction', 3 => 'Percentage Addition', 4 => 'Percentage Deduction'], ['prompt' => Yii::t('app','Select Addition/Deduction Type')]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]ref_type')->dropDownList([0 => 'NA', 1 => 'Fixed Point', 2 => 'Actual'], ['prompt' => Yii::t('app','Select Ref. Type')]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]fixed_point')->textInput(['class' => 'form-control number-validate']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]value')->textInput(['class' => 'form-control number-validate']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]step')->textInput(['class' => 'form-control qty-validate']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($purchaseBasedModel, '[0]formula_code')->dropDownList([]) ?>
        </div>
        <?= Html::hiddenInput('purchase_rate', '', ['id' => 'purchase_rate']); ?>
        <?= Html::hiddenInput('quality_param', $quality_param, ['id' => 'quality_param']); ?>

        <!--<div class="clearfix"></div>-->
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Yii::$app->controls->save('SAVE', $purchaseBasedModel); ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<div class="ex2-grid">
    <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel, 'delete' => true]) ?>
</div>

<?php
$script = "
    var records;
    var jsonEncoded = '" . $jsonEncoded . "';
    if(jsonEncoded==''){
        records = localStorage.getItem('purchaseRate');
    }else{
        records = jsonEncoded;
    }
   $('#purchase_rate').val(records); 
if($('#tbldcspurchaseratebased-0-deduction_type').val()=='' || $('#tbldcspurchaseratebased-0-deduction_type').val()==0){
 $('#tbldcspurchaseratebased-0-ref_type').prop('disabled',true);       
}
   if($('#tbldcspurchaseratebased-0-ref_type').val()==1){
     
   }else if ($('#tbldcspurchaseratebased-0-ref_type').val()==2){
    $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',true);
    $('#tbldcspurchaseratebased-0-step').prop('readonly',true);   
   }else{
   $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',true);
   $('#tbldcspurchaseratebased-0-value').prop('readonly',true);
   $('#tbldcspurchaseratebased-0-step').prop('readonly',true);   
}
  
  // $('#tbldcspurchaseratebased-0-milk_quality_type_code').prop('readonly',true);
  // $('#tbldcspurchaseratebased-0-milk_quality_type_code').prop('disabled',true);
 //  $('#tbldcspurchaseratebased-0-milk_quality_type_code option:selected').text('Good');
     getQualityparam();
 if($('#tbldcspurchaseratebased-0-rate_type :selected').val() != ''){
 $('#tbldcspurchaseratebased-0-quality_param_code').val({$qualityparam});
                     }     

   $('#tbldcspurchaseratebased-0-ref_type').on('change',function(){
        if($(this).val()==1){
            $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',false);
            $('#tbldcspurchaseratebased-0-value').prop('readonly',false);
            $('#tbldcspurchaseratebased-0-step').prop('readonly',false);
        } else if ($(this).val()==2){
            $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-fixed_point').val(''); 
            $('#tbldcspurchaseratebased-0-step').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-step').val('');
            $('#tbldcspurchaseratebased-0-value').prop('readonly',false);
        }else{
           $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-value').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-step').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-fixed_point').val('');
            $('#tbldcspurchaseratebased-0-value').val('');
            $('#tbldcspurchaseratebased-0-step').val('');      
        }
    });
  $('#tbldcspurchaseratebased-0-deduction_type').on('change',function(){
  
  if($('#tbldcspurchaseratebased-0-deduction_type').val()=='' || $('#tbldcspurchaseratebased-0-deduction_type').val()==0){
   $('#tbldcspurchaseratebased-0-ref_type').val($('#tbldcspurchaseratebased-0-deduction_type').val());
   $('#tbldcspurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-value').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-step').prop('readonly',true);
            $('#tbldcspurchaseratebased-0-fixed_point').val('');
            $('#tbldcspurchaseratebased-0-value').val('');
            $('#tbldcspurchaseratebased-0-step').val('');      
   $('#tbldcspurchaseratebased-0-ref_type').prop('disabled',true); 
  }else{
    $('#tbldcspurchaseratebased-0-ref_type').val('');
  $('#tbldcspurchaseratebased-0-ref_type').prop('disabled',false); 
  }
   });
    $('#tbldcspurchaseratebased-0-rate_type').on('change',function(){
        getQualityparam();
    });
        function getQualityparam(){
          var rateType = $('#tbldcspurchaseratebased-0-rate_type :selected').text();
                $('#tbldcspurchaseratebased-0-quality_param_code').empty();
                $('#tbldcspurchaseratebased-0-quality_param_code').append('<option value>Select Quality Param</option>');
                var quality_param=$.parseJSON($('#quality_param').val());
                $.each(rateType.split('+'), function(index, item)
                {
                    var value=0;
                    $.each(quality_param, function(p,p_value) {
                        if(item==p_value){
                            return value=p;
                        }                
                     });    
                   if($('#tbldcspurchaseratebased-0-rate_type :selected').val()==''){
                    return value='';
                     }
                    $('#tbldcspurchaseratebased-0-quality_param_code').append('<option value='+value+'>' + item + '</option>');
                });
        }
    $('#tbldcspurchaseratebased-0-milk_type_code').on('change',function(){

            var data = $('#purchase_rate').val();
            var obj = $.parseJSON(data);
            var milkType = this.value;
            var rateType = $('#tbldcspurchaseratebased-0-rate_type').val();
            var wefDate = obj.wef_date;
            var union_code = obj.union_code;
            $.ajax({
                        type: 'post',
                        url: '" . yii\helpers\Url::to(['formula-master/get-formula']) . "',
                        data: 'wefDate='+wefDate+'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code+'&dropdown=dropdown',
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            $('#tbldcspurchaseratebased-0-formula_code').empty();
                            if (obj1.status == 'success')
                            {
                                $.each( obj1.data, function( key, value ) {
                                    $('select#tbldcspurchaseratebased-0-formula_code').append('<option value='+key+'>'+value+'</option>');
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
    $('#dynamic-form').submit(function(e) {
      $('#tbldcspurchaseratebased-0-ref_type').prop('disabled',false); 
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>
