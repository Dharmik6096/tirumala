<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'Society villages');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">Society Villages for <?= $modelDcs->dcs_name ?></div>
    <div class="panel-body">
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
                    'validateOnEnter' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
                    'fieldConfig' => [
                    //'labelOptions' => [ 'class' => false],
        ]]);
        ?>
        <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>
        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="col-sm-6">
                <div class="btn-group">
                    <span class="input-group-btn">
                        <span id="show-only-selected-routes" class="btn btn-default btn-sm">
                            <i class="fa fa-minus"></i> Show only selected
                        </span>
                        <span id="show-all-routes" class="btn btn-default btn-sm hide">
                            <i class="fa fa-plus"></i> Show all
                        </span>
                    </span>
                </div>
            </div>
            <?php
            $i = 0;
            foreach ($villages as $key => $row) {
                $value = explode('-', $key);
                ?>
                <div class="col-sm-12 dcs-city <?php echo ($i == 0) ? '' : 'mt10' ?>"><i class="fa fa-dot-circle-o"></i><b><?= $value[1] ?></b></div>
                <?php echo Html::activeHiddenInput($model, 'district_code[' . $i . ']', ['value' => $value[0]]) ?>
                <div class="col-sm-12">
                    <?php
                    echo $form->field($model, 'village_code[' . $i . ']')->checkboxList(
                            $row, [
                        'id' => 'routes-list',
                        'class' => 'row',
                        'item' =>
                        function ($index, $label, $name, $checked, $value) use ($selected, $defaultValue, $modelDcs) {
                            $checked = in_array($value, $selected);
                            $modelDcs->dcs_code = Yii::$app->getRequest()->getQueryParam('id');
                            $modelDcs->village_code = $value;
                            $checkChild = $modelDcs->getCheckSubCenterExist();
                            $disabled = (($defaultValue == $value || ($checkChild == 1)) && $checked) ? ' disabled' : '';
                            return "<div class='col-sm-4 dcs-checklist checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                        'value' => $value,
                                        'label' => '<label for="' . $value . '">' . $label . '</label>',
                                        'labelOptions' => [
                                            'class' => 'route-text' . $disabled,
                                        ],
                                        'class' => 'route-checkbox',
                                        'id' => $value,
                                    ]) . "</div></div>";
                        }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php
                        $i++;
                    }
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
        </div>        
        <?php ActiveForm::end(); ?>
        <style>
            .test > label{
                margin-right: 20px;
            }
        </style>
        <?php
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
        ?>