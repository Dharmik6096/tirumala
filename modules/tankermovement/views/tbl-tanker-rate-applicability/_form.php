<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\components\GeneralFunctions;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">

    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', 'form-group col-sm-2', false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 padding_10_0 theme-box theme_border_left theme_border_right theme_border_bottom view-subtitle">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
            <h4 class="theme-box-heading">Apply To</h4>
            <div class="form-group">
                <div class="checkbox app-check-all app-check-list-padding">
                    <label class="route-text">   
                        <input type="checkbox" id="checkAll" class="route-checkbox">
                        <!-- Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) -->
                        <label for="checkAll">Check All</label>
                    </label>
                </div>
            </div>
             <div class="checkbox app-check-all app-check-list-padding">
            <div id="dcs-wrap" class="checkbox">
                <?=
                $this->render('_checkbox_list', [
                    'model' => $model, 'form' => $form, 'field_name' => 'applicable_code',
                    'list' => $party_list, 'selected' => $selected,
                ])
                ?>
            </div>
             </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
	
$('#checkAll').on('change', function() {
        var check =this.checked;
        $('.route-checkbox').each(function () {
            this.checked = check;
        }); 
    });	



";
/* $script = "

  var union='{$selected_union}';
  if(!(union===''))
  {
  $('#union').hide();
  $('.change').each(function(){
  $(this).removeClass('col-sm-4').addClass('col-sm-6');
  });
  }
  $('#tblpurchaserateapplicability-union_code').on('change',function(){
  var code=$(this).val();
  $.ajax({
  type: 'post',
  url: '" . Yii::$app->request->baseUrl . "/organisation/tbl-dcs/get-union-dcs',
  data: 'union='+code,
  success: function(data) {
  var obj1 = $.parseJSON(data);
  if (obj1.status == 'success')
  {
  $('#dcs_code-list').empty();
  $.each(obj1.data, function(index, value) {
  $('#dcs_code-list').append('<div class=\"col-sm-4 dcs-checklist checklist\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"TblTankerRateApplicability[dcs_code][]\" value=\"'+index+'\" id=\"'+index+'\"><label class=\"route-text\" for=\"'+index+'\">'+value+'</label></div></div>');
  });
  }
  },
  error:function(data){
  //alert('Your data has not been submitted..Please try again');
  }
  });
  });
  $('body').on('click','.route-checkbox',function()
  {
  });
  ";
 */

$this->registerJs($script, View::POS_END, 'village-code');

