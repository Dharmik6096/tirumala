<?php

use yii\helpers\Html;
use app\components\ActiveForm;

$title = Yii::$app->label->title('create', 'Symptom Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">

    <div class="panel-heading">Disease : <?= $diseaseMaster->disease_id . '(' . strtoupper($diseaseMaster->disease_name) . ')' ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                        'disease_id' => 'form-group col-sm-2',
                    ],
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
                    'fieldConfig' => [
        ]]);
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h5 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h5>
                </div>
                <?php echo $form->errorSummary($model); ?>
                <?php echo Html::activeHiddenInput($model, 'disease_id', ['value' => $diseaseMaster->disease_id]) ?>
                <div class="col-sm-12 margin-top-10">
                    <div class="col-sm-2  margin-bottom-10">
                        <div class="btn-group">
                            <span class="input-group-btn">
                                <span id="show-only-selected-symptoms" class="btn btn-default btn-sm">
                                    <i class="fa fa-minus"></i> Show only selected
                                </span>
                                <span id="show-all-symptoms" class="btn btn-default hide btn-sm">
                                    <i class="fa fa-plus"></i> Show all
                                </span>
                            </span>
                        </div>
                        <div class="btn-group margin-top-10">
                            <span class="input-group-btn">
                                <span id="check-all-symptoms" class="btn btn-default btn-sm" data-checked ="true">
                                    <i class="fa fa-check-square facheckfalse hide"></i> <i class="fa fa-square-o fachecktrue "></i> Select All
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-2 ">
                    </div>
                    <?php
                    $i = 0;
                    ?>
                    <div class="col-sm-12">
                        <?php
                        echo $form->field($model, 'symptom_id')->checkboxList(
                                $symtom, [
                            'id' => 'symptoms-list',
                            'class' => 'row mb15',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($selected, $defaultValue, $diseaseMaster) {
                                $checked = in_array($value, $selected);
                                $diseaseMaster->disease_id = Yii::$app->getRequest()->getQueryParam('id');
                                $disabled = $checked ? ' disabled' : '';
                                return "<div class='col-sm-2 checklist dcs-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                            'value' => $value,
                                            'label' => '<label for=' . $value . '>' . $label . '</label>',
                                            'labelOptions' => [
                                                'class' => 'route-text' . $disabled,
                                            ],
                                            'class' => 'symptom-checkbox',
                                            'id' => $value,
                                        ]) . "</div></div>";
                            }])->label(false);
                        ?>
                    </div>
                    <?php
                    $i++;
                    ?>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">                    
                            <?= Yii::$app->controls->save($button, $model); ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->cancel($model); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <?=
            $this->render('_source_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
    <?php
    $js = <<<JS

var symptomCheckboxes = $('.symptom-checkbox');
var symptomText = $('.dcs-checklist');

// For checked symptoms
var backgroundColor = '#D6FFDE';
function showAllSymptomsBack() {
	$('.dcs-checklist').each(function(){
		$(this).removeClass('hide');
	});
}


// Highlight selected checkboxes
symptomCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
symptomCheckboxes.on('change', function(){
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

$('#check-all-symptoms').on('click', function(){
    var checkedAll = $(this).attr('data-checked');
    if(checkedAll == 'false'){
        $('.facheckfalse').addClass('hide');
        $('.fachecktrue').removeClass('hide');
        $(this).attr('data-checked','true');
    }else{
        $('.fachecktrue').addClass('hide');
        $('.facheckfalse').removeClass('hide');
        $(this).attr('data-checked','false');
    }
   
	$('.symptom-checkbox').each(function(){
        var _t2 = $(this);
        var isChecked = _t2.is(':checked');
        _t2.prop('checked', checkedAll == 'true' ? true : false);
        _t2.closest('label').css('background', checkedAll == 'true' ? backgroundColor : 'none' );
        
    });
})

// Hide on not selected symptoms
$('#show-only-selected-symptoms').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-symptoms').removeClass('hide');

	symptomCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.dcs-checklist').addClass('hide');
		}
	});
});

// Show all symptoms back
$('#show-all-symptoms').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-symptoms').removeClass('hide');

	showAllSymptomsBack();
});

JS;

    $this->registerJs($js);
    ?>