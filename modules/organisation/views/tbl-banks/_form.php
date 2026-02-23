<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$style = ($model->nationalized_bank == 0) ? 'block' : 'none';
$disable = ($model->isNewRecord) ? '' : 'disabled';
$select = 'block';
if ($model->isNewRecord) {
    $select = 'none';
    $disable = '';
}
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
    <div class="col-sm-2">
        <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form, 'local_short_name'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'old_bank_code')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ac_no_length')->textInput(['readonly' => $disable == '' ? false : true]) ?>
    </div>
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'checked_ac_no'); ?>
    </div>
    <div class="col-sm-2">
        <?php echo Html::hiddenInput('ledger_type', 'bank', ['id' => 'ledger_type']); ?>
        <?php echo Html::hiddenInput('union_code', 'bank', ['id' => 'union_code']); ?>
        <?= Yii::$app->dropdown->ledgerList($model, $form, 'union_code,ledger_type', 'ledger_code', $model->getAttributeLabel('ledger_code'), false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'nationalized_bank'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_alpha_acno_allow'); ?>
    </div>
    <div class="clearfix"></div>   
    <div class="col-sm-12 national-bank" style="display: <?= $style ?>" id="national-bank">
        <?php
        $selectAllChecked = (count($map_model) == count($district) && count($district) != 0) ? 'checked' : '';

        $bankCode = $model->bank_code;
        ?>
        <div class="checkbox select-all-div">
            <input type = "checkbox" id = "select_all" <?= $selectAllChecked ?>><label for = "select_all">Select All</label>
        </div>
        <?php
        echo $form->field($model, 'district[]')->checkboxList(
                $district, [
            'id' => 'district-list',
            'class' => 'row',
            'item' =>
            function ($index, $label, $name, $checked, $value) use ($district, $map_model, $bankCode, $model) {
//                var_dump(count($map_model));exit;
                $checked = in_array($value, $map_model);
                $check = $model->getDistrictUsed($bankCode, $label);
                $disabled = ($checked == 1 && $check == 1) ? ' disabled' : '';
                return "<div class='col-sm-2 dcs-checklist checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                            'value' => $value,
                            'label' => '<label for="' . $value . '">' . $label . '</label>',
                            'labelOptions' => [
                                'class' => 'route-text' . $disabled,
                            ],
                            'class' => 'route-checkbox',
                            'id' => $value,
                        ]) . "</div></div>";
            },
                ]
        )->label(false);
        ?>
        <?php //$form->field($model,'district', [ 'options' => ['class' => 'form-group col-sm-3 '.$disable,]])->listBox($districts['value'],['multiple'=>'multiple','size'=>'10','options'=>$districts['selected']]); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
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

$('#tblbanks-state').change(function() {
	   $.ajax({
				type: 'post',
                                url: '" . Url::to(['/organisation/tbl-banks/district-list']) . "',
				data: {'id':$('#tblbanks-state').val(),'code':'$model->bank_code'},
				success: function(data) {                                        
					var obj1 = $.parseJSON(data);
					if (obj1.status == 'success')
					{
                                                $('#select_all').prop('checked', false);
						var content = '';
                                                var count = 0;
                            
						$.each(obj1.output, function(idx, obj){
							var sel = jQuery.inArray(idx,obj1.selected);
							content+='<div class=\"col-sm-4 dcs-checklist\"><div class=\"checkbox\"><label class=\"route-text '+obj1.disabled[idx]+'\"><input class=\"route-checkbox\" name=\"TblBanks[district][]\" id=\"'+idx+'\" value=\"'+idx+'\" '+obj1.checked[idx]+' type=\"checkbox\"><label for=\"'+idx+'\">'+obj+'</label></div></div>';
						});
                                                var totalcount = $('#totalcount').val();
                                                if(parseInt(totalcount)==parseInt(count) && (parseInt(totalcount)!='0' && parseInt(count)!='0')){
                                                    $('#select_all').prop('checked', true);
                                                }
						$('#tblbanks-state').parent().addClass(obj1.disableState);

						$('#routes-list').empty();
						$('#routes-list').append( content );
                                                $('.select-all-div').show();
						$('#district-list').empty();
						$('#district-list').append( content );
		}
				},
				error:function(data){
							//alert('Your data has not been submitted..Please try again');
				}
	});


});


$('#tblbanks-nationalized_bank').change(function() {
	if($(this).is(':checked')) {
		$('.national-bank').css('display','none');
//                $('#district-list').css('display','none');
	}else{
		$('.national-bank').css('display','block');
//                $('#district-list').css('display','block');
	}
});

$('button[type=\'reset\']').click(function(){
	$('.national-bank').css('display','block');
//        $('#district-list').css('display','block');
//	$('#district-list').val.empty();
});

$('#select_all').change(function(){
    $('.route-checkbox').prop('checked', $(this).prop('checked'));
});

$('#district-list').on('change','.route-checkbox',function() {
    if(false == $(this).prop('checked')) {
        $('#select_all') . prop('checked', false);
    }

    if ($('.route-checkbox:checked').length == $('.route-checkbox').length ) {
        $('#select_all') . prop('checked', true);
    }
});
";
$this->registerJs($script, View::POS_END, 'bank-select');
