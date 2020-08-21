<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Farmer Payment Process : Step 1';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    //'action' => ['list-payment'],
                    //'method' => 'GET',
                    'validateOnBlur' => false,
                    
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->unionpaymentcycle($model, $form, 'tblmemberpayment-union_code', 'dcs_payment_cycle_code', 'Payment Cycle'); ?>
            </div>
        </div>
        <div id="list-title" style="display: none">
            <h4>Society List</h4>
            <div class="form-group">
                <div class="checkbox app-check-all">
                    <label class="route-text">
                        <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                        <label for="checkAll">Select All Societies</label>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" id="society-list">

        </div>
        <div class="row">
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>                
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
    $(document).ready(function(){
        if($('#tblmemberpayment-dcs_payment_cycle_code').val()!=='')
        {            
            $('#tblmemberpayment-dcs_payment_cycle_code').change();
        }
    });
$('#tblmemberpayment-dcs_payment_cycle_code').change(function() {
	   $.ajax({
            type: 'post',
            url:'" . Url::to(['list-society']) . "',
            data: {'payment_cycle':$(this).val()},
            success: function(data) {                                        
                  var obj = $.parseJSON(data);
                  if (obj.status == 'success')
                  {
                    $('#society-list').empty();
                    $.each(obj.data.list, function(index, value) {
                     //   var disable = ($.inArray( value.dcs_code, obj.data.processed) !== -1)?'disabled':'checked';
                        var disable = (value.data_lock==1)?'disabled':'checked';
                        $('#society-list').append('<div class=\"col-sm-4 dcs-checklist checklist\" id=\"nd-'+value.dcs_code+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"TblMemberPayment[dcs_code][]\" value=\"'+value.dcs_code+'\" id=\"'+value.dcs_code+'\" ' + disable+ '><label class=\"route-text\" for=\"'+value.dcs_code+'\">'+value.dcs_name+'</label></div></div>');
                    });
                  }
                  $('#checkAll').prop('checked', true);
                  $('#list-title').show();
            },
            error:function(data){
		
	    }
	});


});
 $('#checkAll').click(function () {    
        $('#society-list').find('.route-checkbox:enabled').prop('checked', this.checked);    
    });

";
$this->registerJs($script, View::POS_END, 'bank-select');

