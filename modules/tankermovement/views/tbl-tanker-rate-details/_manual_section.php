<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'Tanker Rate - Manually');
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

<?php
echo $form->errorSummary($purchaseBasedModel);
$qualityparam = '0';
?>

<div class="row">
    <div class="col-sm-2">
        <?php
        echo Yii::$app->dropdown->dropdownStatic('tanker_rate_type', $purchaseBasedModel, $form, '', $purchaseBasedModel->getAttributeLabel('rate_type_code'));
        ?>                                 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel, $form, '', 'Milk Type', false, 'milk_type_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $purchaseBasedModel, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6" id="range"></div>
    <?= Html::activeHiddenInput($purchaseBasedModel, 'purchase_rate') ?>
</div>
<hr class="hr10">
<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save('SAVE', $purchaseBasedModel); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($purchaseBasedModel, ['../tbl-tanker-rate/index']); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<hr class="hr10">
<div class="row">

    <div class="form-grid">
        <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel]) ?>
    </div>
</div>

<?php
$script = "
    var purchaseRate = localStorage.getItem('purchaseRate');
    $('#tbltankerratebased-purchase_rate').val(purchaseRate);
       $('#tbltankerratebased-rate_type_code').on('change',function(e){
        $('#range').empty();       
       var rateType = $('#tbltankerratebased-rate_type_code :selected').text();     
        var field_before = '<div class=\"col-sm-3\"><div class=\"form-group\">';
        var field_after = '';
        $('#range').append(field_before + '<label class=\"control-label\">Std FAT*</label><input type=\"text\" name=\"TblTankerRateBased[std_fat]\" id=\"tbltankerratebased-std_fat\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Std SNF*</label><input type=\"text\" name=\"TblTankerRateBased[std_snf]\" id=\"tbltankerratebased-std_snf\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Base Rate*</label><input type=\"text\" name=\"TblTankerRateBased[base_rate]\" id=\"tbltankerratebased-base_rate\" class=\"form-control number-validate\">');
             
      if($('#tbltankerratebased-rate_type_code').val()!=''){
       if (rateType== 'FAT+SNF')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">Fat Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[fat_ratio]\" id=\"tbltankerratebased-fat_ratio\" class=\"form-control get-fat-rate\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[snf_ratio]\" id=\"tbltankerratebased-snf_ratio\" class=\"form-control get-snf-rate\">' + field_after);
             
            $('#range').append(field_before + '<label class=\"control-label\">Fat Rate*</label><input type=\"text\" name=\"TblTankerRateBased[fat_rate]\" id=\"tbltankerratebased-fat_rate\" class=\"form-control\" readonly=\"true\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Rate*</label><input type=\"text\" name=\"TblTankerRateBased[snf_rate]\" id=\"tbltankerratebased-snf_rate\" class=\"form-control\" readonly=\"true\">' + field_after);
       
            var specialDecimalKeys = new Array();
                specialDecimalKeys.push(8);
                
            $('.get-fat-rate').bind('keyup', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            var ratio = $('#tbltankerratebased-fat_ratio').val();
            var std = $('#tbltankerratebased-std_fat').val();
            var base_rate = $('#tbltankerratebased-base_rate').val();
            var rate = Math.round(ratio *base_rate /std,2);
             if(rate>0 && ratio>0 && base_rate>0 && std>0)
             {
                $('#tbltankerratebased-fat_rate').val(parseFloat(rate).toFixed(2));
                 
            }
            return ret;
        });
        
          $('.get-snf-rate').bind('keyup', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            var ratio = $('#tbltankerratebased-snf_ratio').val();
            var std = $('#tbltankerratebased-std_snf').val();
            var base_rate = $('#tbltankerratebased-base_rate').val();
            var rate = ratio *base_rate /std;
             if(rate>0 && ratio>0 && base_rate>0 && std>0)
             {
                $('#tbltankerratebased-snf_rate').val(parseFloat(rate).toFixed(2));
            }
            return ret;
        });
       }
       
        if (rateType== 'QTY')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">QTY Rate*</label><input type=\"text\" name=\"TblTankerRateBased[qty_rate]\" id=\"tbltankerratebased-qty_rate\" class=\"col-sm-2 form-control number-validate\">' + field_after);
            var specialDecimalKeys = new Array();
                specialDecimalKeys.push(8);
            $('.number-validate').bind('keypress', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
        });
       }
      }
    });
";
$this->registerJs($script, View::POS_END, 'sample-download');
?>
