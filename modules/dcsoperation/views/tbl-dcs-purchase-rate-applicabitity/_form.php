<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\components\GeneralFunctions;
use yii\helpers\Url;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$title = Yii::$app->label->title('create', 'Dcs Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-main">
    <div class="panel-heading">Dcs Mapping for <?= $purchaseRate->purchase_rate_code ?></div>
    <?php
    $form = ActiveForm::begin(['options' => [
                    'class' => 'save-form',
                    //                'field-class' => 'form-group col-sm-3',
                    'union_code' => 'form-group col-sm-3',
                    'milk_quality_type_code' => 'form-group col-sm-2',
                    'route_code' => 'form-group col-sm-2',
                    'tbldcs-is_bmc' => 'form-group col-sm-3'
                ],
                'validateOnBlur' => false,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
    ]]);
    ?>
    <div class="panel-body">
        <div class="panel-subheading">
            <h5 class="panel-subtitle">
                <?php echo Yii::t('app', $title); ?>
            </h5>
            <?php echo $form->errorSummary($model); ?>
            <!--<div class="row">-->

            <div class="row">
                <?= Yii::$app->controls->date($model, $form, 'wef_date', 'form-group col-sm-3'); ?>

                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'form-group col-sm-6', 'Shift',false,'shift_code'); ?>
                <div class="col-sm-3">
                    <div class="checkbox">
                        <?= $form->field($model, 'organization')->radioList([0 => 'Dcs'])->label(false); ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <?php echo $form->field($model, 'route_code', [ 'options' => ['class' => 'form-group col-sm-6',]])->listBox($routes['routes'], ['multiple' => 'multiple', 'size' => '10', 'options' => $routes['selectedRoutes']]); ?>

                <?php echo $form->field($model, 'dcs_code', [ 'options' => ['class' => 'form-group col-sm-6',]])->listBox($routes['selectedAllOrg'], ['multiple' => 'multiple', 'size' => '10', 'options' => $routes['selectedOrganization']])->label('Organization'); ?>
            </div>
        </div>
    </div>
    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Html::button(Yii::t('app', $button), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => $model->isNewRecord ? 'ctrl+alt+s' : 'ctrl+alt+u', 'button' => 'save']) ?>
        <?= Html::resetButton(Yii::t('app', 'reset'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+r']) ?>
        <?php echo Html::a('cancel', ['index'], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = "
    $('#tbldcspurchaserateapplicabitity-route_code').on('change',function(){          
             var route = []; 
             $('#tbldcspurchaserateapplicabitity-route_code :selected').each(function(i, selected){ 
                route[i] = $(selected).val(); 
              });
             var org = $('input[name=\'TblDcsPurchaseRateApplicabitity[organization]\']:checked').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/dcsoperation/tbl-head-load/get-dcs']) . "',    
                        data: 'route='+route+'&org='+org,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tbldcspurchaserateapplicabitity-dcs_code').empty();
                                $.each(obj1.data, function(index, value) {
                                    $('#tbldcspurchaserateapplicabitity-dcs_code').append($('<option>').text(value).val(index));
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
    
    $('input[name=\'TblDcsPurchaseRateApplicabitity[organization]\']').on('change',function(){ 
            $('#tbldcspurchaserateapplicabitity-dcs_code').empty();
            $('#tbldcspurchaserateapplicabitity-route_code option').attr('selected',false);
    });
";


$this->registerJs($script, View::POS_END, 'village-code');
$js = <<<JS

                
                
var routeCheckboxes = $('.route-checkbox');
var routeText = $('.dcs-checklist');

// For checked routes
var backgroundColor = '#D6FFDE';
function showAllRoutesBack() {
	$('.dcs-checklist').each(function(){
		$(this).removeClass('hide');
	});
}


// Highlight selected checkboxes
routeCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
routeCheckboxes.on('change', function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
	else
	{
		_t.closest('label').css('background', 'none');
	}
});


// Hide on not selected routes
$('#show-only-selected-routes').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-routes').removeClass('hide');

	routeCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.dcs-checklist').addClass('hide');
		}
	});
});

// Show all routes back
$('#show-all-routes').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-routes').removeClass('hide');

	showAllRoutesBack();
});

JS;

$this->registerJs($js);
