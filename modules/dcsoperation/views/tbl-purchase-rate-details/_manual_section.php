<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'Purchase Rate - Manually');
//$button = Yii::$app->label->button($type);
?>
<?php
$form = ActiveForm::begin(['id' => 'dynamic-form',
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<h5 class="panel-subtitle"><?php echo Yii::t('app', 'Purchase Rate - Manual'); ?></h5>

<?php
echo $form->errorSummary($purchaseBasedModel);
$qualityparam = $purchaseBasedModel->quality_param_code;
?>

<div class="row">
    <div class="col-sm-3 change">
        <?= Yii::$app->dropdown->dropdown('rate_type_code', $purchaseBasedModel, $form, '', 'Rate Type', false, '[0]rate_type_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $purchaseBasedModel, $form, '', 'Milk Quality Type', false, '[0]milk_quality_type_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel, $form, '', 'Milk Type', false, '[0]milk_type_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]quality_param_code')->dropDownList([], ['prompt' => 'Select Quality Param']); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]start_range')->textInput(['class' => 'form-control number-validate'])->label('Start') ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]end_range')->textInput(['class' => 'form-control number-validate'])->label('End') ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]kg_rate')->textInput(['class' => 'form-control number-validate']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]deduction_type')->dropDownList([0 => 'NA', 1 => 'Value Addition', 2 => 'Value Deduction', 3 => 'Percentage Addition', 4 => 'Percentage Deduction'], ['prompt' => 'Select Deduction Type']); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]ref_type')->dropDownList([0 => 'NA', 1 => 'Fixed Point', 2 => 'Actual'], ['prompt' => 'Select Ref. Type']); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]fixed_point')->textInput(['class' => 'form-control number-validate']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]value')->textInput(['class' => 'form-control number-validate']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($purchaseBasedModel, '[0]step')->textInput(['class' => 'form-control qty-validate']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($purchaseBasedModel, '[0]formula')->textInput(['readOnly' => true]) ?>
    </div>
    <?= Html::activeHiddenInput($purchaseBasedModel, '[0]formula_code'); ?>
    <?= Html::hiddenInput('purchase_rate', '', ['id' => 'purchase_rate']); ?>
    <?= Html::hiddenInput('quality_param', $quality_param, ['id' => 'quality_param']); ?>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('SAVE', $purchaseBasedModel); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<hr class="hr10">

<div class="row">
    <div class="form-grid">
        <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel,'delete' => true]) ?>
    </div>
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
if($('#tblpurchaseratebased-0-deduction_type').val()=='' || $('#tblpurchaseratebased-0-deduction_type').val()==0){
 $('#tblpurchaseratebased-0-ref_type').prop('disabled',true);       
}
   if($('#tblpurchaseratebased-0-ref_type').val()==1){
     
   }else if ($('#tblpurchaseratebased-0-ref_type').val()==2){
    $('#tblpurchaseratebased-0-fixed_point').prop('readonly',true);
    $('#tblpurchaseratebased-0-step').prop('readonly',true);   
   }else{
   $('#tblpurchaseratebased-0-fixed_point').prop('readonly',true);
   $('#tblpurchaseratebased-0-value').prop('readonly',true);
   $('#tblpurchaseratebased-0-step').prop('readonly',true);   
}
  
  // $('#tblpurchaseratebased-0-milk_quality_type_code').prop('readonly',true);
   $('#tblpurchaseratebased-0-milk_quality_type_code').prop('disabled',true);
   $('#tblpurchaseratebased-0-milk_quality_type_code option:selected').text('Good');
     getQualityparam();
 if($('#tblpurchaseratebased-0-rate_type_code :selected').val() != ''){
 $('#tblpurchaseratebased-0-quality_param_code').val({$qualityparam});
                     }     

   $('#tblpurchaseratebased-0-ref_type').on('change',function(){
        if($(this).val()==1){
            $('#tblpurchaseratebased-0-fixed_point').prop('readonly',false);
            $('#tblpurchaseratebased-0-value').prop('readonly',false);
            $('#tblpurchaseratebased-0-step').prop('readonly',false);
        } else if ($(this).val()==2){
            $('#tblpurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tblpurchaseratebased-0-fixed_point').val(''); 
            $('#tblpurchaseratebased-0-step').prop('readonly',true);
            $('#tblpurchaseratebased-0-step').val('');
            $('#tblpurchaseratebased-0-value').prop('readonly',false);
        }else{
           $('#tblpurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tblpurchaseratebased-0-value').prop('readonly',true);
            $('#tblpurchaseratebased-0-step').prop('readonly',true);
            $('#tblpurchaseratebased-0-fixed_point').val('');
            $('#tblpurchaseratebased-0-value').val('');
            $('#tblpurchaseratebased-0-step').val('');      
        }
    });
  $('#tblpurchaseratebased-0-deduction_type').on('change',function(){
  
  if($('#tblpurchaseratebased-0-deduction_type').val()=='' || $('#tblpurchaseratebased-0-deduction_type').val()==0){
   $('#tblpurchaseratebased-0-ref_type').val($('#tblpurchaseratebased-0-deduction_type').val());
   $('#tblpurchaseratebased-0-fixed_point').prop('readonly',true);
            $('#tblpurchaseratebased-0-value').prop('readonly',true);
            $('#tblpurchaseratebased-0-step').prop('readonly',true);
            $('#tblpurchaseratebased-0-fixed_point').val('');
            $('#tblpurchaseratebased-0-value').val('');
            $('#tblpurchaseratebased-0-step').val('');      
   $('#tblpurchaseratebased-0-ref_type').prop('disabled',true); 
  }else{
    $('#tblpurchaseratebased-0-ref_type').val('');
  $('#tblpurchaseratebased-0-ref_type').prop('disabled',false); 
  }
   });
    $('#tblpurchaseratebased-0-rate_type_code').on('change',function(){
        getQualityparam();
    });
        function getQualityparam(){
          var rateType = $('#tblpurchaseratebased-0-rate_type_code :selected').text();
                $('#tblpurchaseratebased-0-quality_param_code').empty();
                $('#tblpurchaseratebased-0-quality_param_code').append('<option value>Select Quality Param</option>');
                var quality_param=$.parseJSON($('#quality_param').val());
                $.each(rateType.split('+'), function(index, item)
                {
                    var value=0;
                    $.each(quality_param, function(p,p_value) {
                        if(item==p_value){
                            return value=p;
                        }                
                     });    
                   if($('#tblpurchaseratebased-0-rate_type_code :selected').val()==''){
                    return value='';
                     }
                    $('#tblpurchaseratebased-0-quality_param_code').append('<option value='+value+'>' + item + '</option>');
                });
        }
    $('#tblpurchaseratebased-0-milk_type_code').on('blur',function(){

            var data = $('#purchase_rate').val();
            var obj = $.parseJSON(data);
            var milkType = this.value;
            var rateType = $('#tblpurchaseratebased-0-rate_type_code').val();
            var wefDate = obj.wef_date;
            var union_code = obj.union_code;
            $.ajax({
                        type: 'post',
                        url: '" . yii\helpers\Url::to(['formula-master/get-formula']) . "',
                        data: 'wefDate='+wefDate+'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            $('#tblpurchaseratebased-0-formula_code').empty();
                            $('#tblpurchaseratebased-0-formula').empty();
                            if (obj1.status == 'success')
                            {
                                $('#tblpurchaseratebased-0-formula_code').val(obj1.data.code);
                                $('#tblpurchaseratebased-0-formula').val(obj1.data.formula);
                            }else{
                                $('#tblpurchaseratebased-0-formula_code').val('');
                                $('#tblpurchaseratebased-0-formula').val('');
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
    $('#dynamic-form').submit(function(e) {
      $('#tblpurchaseratebased-0-ref_type').prop('disabled',false); 
    });
";
$this->registerJs($script, View::POS_END, 'manual-rate-chart');
?>
