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
<h5 class="panel-subtitle"><?php echo Yii::t('app', 'Tanker Rate - Manual'); ?></h5>

<?php
echo $form->errorSummary($purchaseBasedModel);
$qualityparam = '0';
?>

<div class="row">
    <div class="col-sm-2">
        <?php
        $rate_type = array("1" => "FAT+SNF", "2" => "QTY");
        echo $form->field($purchaseBasedModel, 'rate_type_code')->dropDownList($rate_type, ['prompt' => Yii::t('app', 'Select Rate Type')])->label(Yii::t('app', 'Rate Type'));
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
    <div  id="range"></div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('SAVE', $purchaseBasedModel); ?>

        </div>
    </div>
</div>
<hr class="hr10">
<?php ActiveForm::end(); ?>


<div class="row">
    <div class="form-grid">
        <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel, 'delete' => true]) ?>
    </div>
</div>
<?php
$script = "
       $('#tbltankerratebased-rate_type_code').on('change',function(e){
        $('#range').empty();       
       var rateType = $('#tbltankerratebased-rate_type_code :selected').text();     
        var field_before = '<div class=\"col-sm-3\"><div class=\"form-group\">';
        var field_after = '';
        $('#range').append(field_before + '<label class=\"control-label\">Std Fat*</label><input type=\"text\" name=\"TblTankerRateBased[std_fat]\" id=\"tbltankerratebased-std_fat\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Std SNF*</label><input type=\"text\" name=\"TblTankerRateBased[std_snf]\" id=\"tbltankerratebased-std_snf\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Base Rate*</label><input type=\"text\" name=\"TblTankerRateBased[base_rate]\" id=\"tbltankerratebased-base_rate\" class=\"form-control number-validate\">' + field_after);
             
      if($('#tbltankerratebased-rate_type_code').val()!=''){
      // console.log($('#tbltankerratebased-rate_type_code').val());
       if (rateType== 'FAT+SNF')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">Fat Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[fat_ratio]\" id=\"tbltankerratebased-fat_ratio\" class=\"form-control get-fat-rate\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[snf_ratio]\" id=\"tbltankerratebased-snf_ratio\" class=\"form-control get-snf-rate\">' + field_after);
             
            $('#range').append(field_before + '<label class=\"control-label\">Fat Rate*</label><input type=\"text\" name=\"TblTankerRateBased[fat_rate]\" id=\"tbltankerratebased-fat_rate\" class=\"form-control\" disabled=\"true\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Rate*</label><input type=\"text\" name=\"TblTankerRateBased[snf_rate]\" id=\"tbltankerratebased-snf_rate\" class=\"form-control\" disabled=\"true\">' + field_after);

            var specialDecimalKeys = new Array();
                specialDecimalKeys.push(8);
                
            $('.get-fat-rate').bind('keyup', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            var ratio = $('#tbltankerratebased-fat_ratio').val();
            var std = $('#tbltankerratebased-std_fat').val();
            var base_rate = $('#tbltankerratebased-base_rate').val();
            var rate = ratio *base_rate /std;
             console.log(rate);
             if(rate>0 && ratio>0 && base_rate>0 && std>0)
             {
                var inputF = document.getElementById('tbltankerratebased-fat_rate');
                inputF.value = rate;
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
             console.log(rate);
             if(rate>0 && ratio>0 && base_rate>0 && std>0)
             {
              var inputF = document.getElementById('tbltankerratebased-snf_rate');
                inputF.value = rate;
            }
            return ret;
        });
       }
       
        if (rateType== 'QTY')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">QTY Rate*</label><input type=\"text\" name=\"TblTankerRateBased[qty_rate]\" id=\"tbltankerratebased-qty_rate\" class=\"form-control number-validate\">' + field_after);
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
