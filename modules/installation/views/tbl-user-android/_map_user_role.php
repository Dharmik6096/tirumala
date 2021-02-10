<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'Role');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= Yii::t('app', ' Role For ') . ' ' .$userModel->username ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                        'user_code' => 'form-group col-sm-3',
                        'role_code' => 'form-group col-sm-2',
                    ],
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
                    'fieldConfig' => [
        ]]);
        ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
            <h4 class="theme-box-heading"><?php echo Yii::t('app', $title) ?></h4>
        </div>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="btn-group">
                    <span class="input-group-btn">
                        <span id="show-only-selected-roles" class="btn btn-default btn-sm">
                            <i class="fa fa-minus"></i> Show only selected
                        </span>
                        <span id="show-all-roles" class="btn btn-default hide btn-sm">
                            <i class="fa fa-plus"></i> Show all
                        </span>
                    </span>
                </div>
            </div>
                <div class="col-sm-12">
                    <?php
                    echo $form->field($model, 'role_code')->checkboxList(
                            $menuArray, [
                        'id' => 'roles-list',
                        'class' => 'row mb15',
                        'item' =>
                        function ($index, $label, $name, $checked, $menuArray) use ($selectedArray) {
                            $checked = in_array($menuArray, $selectedArray);
                            $disabled = '';
                            return "<div class='col-sm-4 checklist user-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                        'value' => $menuArray,
                                        'label' => '<label for=' . $menuArray . '>' . $label . '</label>',
                                        'labelOptions' => [
                                            'class' => 'role-text' . $disabled,
                                        ],
                                        'class' => 'role-checkbox',
                                        'id' => $menuArray,
                                    ]) . "</div></div>";
                        }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);
                     ?>
             </div>
                       
                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">                    
                            <?= Yii::$app->controls->save($button, $model); ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->cancel($model); ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <style>
            .test > label{
                margin-right: 20px;
            }
        </style>
        <?php
        $js = <<<JS

var roleCheckboxes = $('.role-checkbox');
var roleText = $('.user-checklist');

// For checked roles
var backgroundColor = '#D6FFDE';
function showAllRolesBack() {
	$('.user-checklist').each(function(){
		$(this).removeClass('hide');
	});
}


// Highlight selected checkboxes
roleCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
roleCheckboxes.on('change', function(){
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


// Hide on not selected roles
$('#show-only-selected-roles').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-roles').removeClass('hide');

	roleCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.user-checklist').addClass('hide');
		}
	});
});

// Show all routes back
$('#show-all-roles').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-roles').removeClass('hide');

	showAllRolesBack();
});

JS;

        $this->registerJs($js);
        ?>