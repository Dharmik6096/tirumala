<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$title = Yii::$app->label->title('create', 'Conversion Vendor Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">Plant : <?= $plantModel->name . ' - ' . $plantModel->ref_code ?> </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
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
                <div class="col-sm-12 margin-top-10">
                    <div class="col-sm-2  margin-bottom-10">
                        <div class="btn-group">
                            <span class="input-group-btn">
                                <span id="show-only-selected-party" class="btn btn-default btn-sm">
                                    <i class="fa fa-minus"></i> Show only selected
                                </span>
                                <span id="show-all-party" class="btn btn-default hide btn-sm">
                                    <i class="fa fa-plus"></i> Show all
                                </span>
                            </span>
                        </div>
                        <div class="btn-group margin-top-10">
                            <span class="input-group-btn">
                                <span id="check-all-party" class="btn btn-default btn-sm" data-checked ="true">
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
                        echo $form->field($model, 'party_master_code')->checkboxList($list, [
                            'id' => 'party-list',
                            'class' => 'row mb15',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($selected, $model) {
                                $checked = in_array($value, $selected);
                                $model->party_master_code = Yii::$app->getRequest()->getQueryParam('id');
                                $disabled = $checked ? ' disabled' : '';
                                return "<div class='col-sm-2 checklist dcs-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                            'value' => $value,
                                            'label' => '<label for=' . $value . '>' . $label . '</label>',
                                            'labelOptions' => [
                                                'class' => 'party-text' . $disabled,
                                            ],
                                            'class' => 'party-checkbox',
                                            'id' => $value,
                                        ]) . "</div></div>";
                            },])->label(false);
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
                            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
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
</div>
<?php
$js = <<<JS

var partyCheckboxes = $('.party-checkbox');
var partyText = $('.dcs-checklist');

// For checked party
var backgroundColor = '#D6FFDE';
function showAllRoutesBack() {
	$('.dcs-checklist').each(function(){
		$(this).removeClass('hide');
	});
}


// Highlight selected checkboxes
partyCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
partyCheckboxes.on('change', function(){
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

$('#check-all-party').on('click', function(){
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
   
	$('.party-checkbox').each(function(){
        var _t2 = $(this);
        var isChecked = _t2.is(':checked');
        _t2.prop('checked', checkedAll == 'true' ? true : false);
        _t2.closest('label').css('background', checkedAll == 'true' ? backgroundColor : 'none' );
        
    });
})

// Hide on not selected party
$('#show-only-selected-party').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-party').removeClass('hide');

	partyCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.dcs-checklist').addClass('hide');
		}
	});
});

// Show all party back
$('#show-all-party').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-party').removeClass('hide');

	showAllRoutesBack();
});

JS;

$this->registerJs($js);
?>