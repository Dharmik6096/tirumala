<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'create-scheme-application-form'],
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblschemeapplication-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblschemeapplication-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblschemeapplication-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?php
        $where = json_encode(['is_welfare_scheme' => 1]);
        $notInArr = json_encode([]);
        echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
        echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
        echo Yii::$app->dropdown->customerType($model, $form, 'tblschemeapplication-union_code,customer_type_depends,customer_type_depends_not_in', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE, FALSE);
        ?>
    </div>
    <div class="col-sm-2" id="dcs-dd">
        <?php echo Yii::$app->dropdown->bmc_society($model, $form, 'tblschemeapplication-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'application_date', '', true); ?>
    </div>
    <div class="col-sm-2">
        <?php echo Yii::$app->dropdown->welfareScheme($model, $form, 'tblschemeapplication-union_code,tblschemeapplication-application_date', 'scheme_id', $model->getAttributeLabel('scheme_id')); ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'ex_code')->textInput(); ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= Html::activeHiddenInput($model, 'customer_code') ?>
        <?= $form->field($model, 'customer_name')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'min_pouring_day')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'actual_pouring_day')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'min_pouring_qty')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'actual_pouring_qty')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-1 reset_field">
        <?= $form->field($model, 'scheme_value')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea([]); ?>
    </div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('NEXT', $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>  
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    setCustomerType();
    $(document).on('change', '#tblschemeapplication-ex_code', function() {  
        setVendorCode();
    });
    $(document).on('change', '#tblschemeapplication-customer_type', function() {  
        setCustomerType();
        resetField();
    });
    $('#tblschemeapplication-application_date').change(function(){
        resetField();
    });
    $('#tblschemeapplication-scheme_id').change(function(){
        resetField();
    });
  function resetField(){
     $('#create-scheme-application-form .reset_field input').val('');
  }  
  function setCustomerType(){
    var type= $('#tblschemeapplication-customer_type').val(); 
    $('#dcs-dd').hide();
    if(type=='MEMBER'){
      $('#dcs-dd').show();
    }
  } 
  function setVendorCode(){
        var code = $('#tblschemeapplication-ex_code').val();
        var date= $('#tblschemeapplication-application_date').val(); 
        var schemeId = $('#tblschemeapplication-scheme_id').val(); 
        var type= $('#tblschemeapplication-customer_type').val(); 
      if(setData(date) && setData(schemeId) && setData(code) && setData(type)){
        var union= $('#tblschemeapplication-union_code').val(); 
        var bmc= $('#tblschemeapplication-bmc_code').val(); 
        var plant= $('#tblschemeapplication-plant_code').val(); 
        var mcc= $('#tblschemeapplication-mcc_plant_code').val(); 
        var dcsCode = $('#tblschemeapplication-dcs_code').val(); 
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-customer']) . "',
                data: {'customer_code':code, 'dcsCode': dcsCode,'customer_type':type,'union_code':union,'bmc_code':bmc,'date':date,'plant':plant,'mcc':mcc,'schemeId':schemeId},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblschemeapplication-customer_name').val(obj.customer_name); 
                        $('#tblschemeapplication-customer_code').val(obj.customer_code);
                        $('#tblschemeapplication-min_pouring_day').val(obj.min_pouring_day); 
                        $('#tblschemeapplication-min_pouring_qty').val(obj.min_pouring_qty);
                        $('#tblschemeapplication-actual_pouring_day').val(obj.actual_pouring_day); 
                        $('#tblschemeapplication-actual_pouring_qty').val(obj.actual_pouring_qty);
                        $('#tblschemeapplication-scheme_value').val(obj.scheme_value); 
                    }else{
                        //Please enter valid Code(Last 4 digit)
                        $('#tblschemeapplication-ex_code').val('');
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Please enter valid Member Code.') . "</span></div></div>', function(result){
                            setTimeout(function(){
                                $('#tblschemeapplication-ex_code').focus();
                            },100);
                        });           
                       resetField();                  
                        $('#tblschemeapplication-ex_code').focus();
                    }
                }
            });
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }     
  }";
$this->registerJs($script, View::POS_END, 'create-scheme-application-form');
?>
