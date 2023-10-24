<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('create', 'Approval Stages');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">Scheme Name : <?= $model->schemeId->scheme_name ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'fieldConfig' => [
        ]]);
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h5 class="theme-box-heading"><?= $this->title; ?></h5>
                </div>
                <?php echo $form->errorSummary($model); ?>
                <div class="row d-block">
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('approval_level', $model, $form, '', $model->getAttributeLabel('level') . '&nbsp;&nbsp;&nbsp;<i class="fa fa-info-circle" title="Level 1 - Primary Approval & Level 6 - Higher Approval"></i>', false, 'level', FALSE, FALSE, FALSE); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('approval_mode', $model, $form, '', $model->getAttributeLabel('approval_mode'), false, 'approval_mode', FALSE, FALSE, FALSE); ?>
                    </div>
                    <div class="col-sm-12 margin-top-10">
                        <h4 class="theme-box-heading padding_left_0 padding_right_0"><?= Yii::t('app', 'User List') ?></h4>
                        <div class="col-sm-12  margin-bottom-10 margin-top-10">
                            <div class="col-sm-2 btn-group">
                                <span class="input-group-btn">
                                    <span id="show-only-selected-users" class="btn btn-default btn-sm">
                                        <i class="fa fa-minus"></i> Show only selected
                                    </span>
                                    <span id="show-all-users" class="btn btn-default hide btn-sm">
                                        <i class="fa fa-plus"></i> Show all
                                    </span>
                                </span>
                            </div>
                            <div class="col-sm-10 form-group">
                                <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => 'user', 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <?php
                            $selected = !empty($model->user_code) ? $model->user_code : [];
                            echo $form->field($model, 'user_code')->checkboxList(
                                    $user_list, [
                                'id' => 'user-list',
                                'class' => 'row mb15',
                                'item' =>
                                function ($index, $label, $name, $checked, $value) use ($selected) {
                                    $checked = in_array($value, $selected);
                                    return "<div class='col-sm-2 checklist user-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                                'value' => $value,
                                                'label' => '<label for=' . $value . '>' . $label . '</label>',
                                                'class' => 'user-checkbox',
                                                'id' => $value,
                                            ]) . "</div></div>";
                                }])->label(false);
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">                    
                                <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <?=
                $this->render('_approval_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
var userCheckboxes = $('.user-checkbox');
var userText = $('.user-checklist');

// For checked user
var backgroundColor = '#D6FFDE';
function showAllUsersBack() {
	$('.user-checklist').each(function(){
		$(this).removeClass('hide');
	});
}

// Highlight selected checkboxes
userCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
userCheckboxes.on('change', function(){
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


// Hide on not selected 
$('#show-only-selected-users').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-users').removeClass('hide');

	userCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.user-checklist').addClass('hide');
		}
	});
});

// Show all back
$('#show-all-users').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-users').removeClass('hide');

	showAllUsersBack();
});

";
$script .= "$('.kv-panel-before').hide();
    function checkBoxFilter(val){
        var id = $(val).attr('id');
        var value = $(val).val();
        count = 0;
        $('#'+id+'-list div').each(function() {
            if ($(this).text().search(new RegExp(value, 'i')) < 0) {
                $(this).hide();
                $(this).find(':input').prop('disabled', true);
            } else {
                $(this).show();
                $(this).find(':input').prop('disabled', false);
                count++;
            }
        });
    }";
$this->registerJs($script, View::POS_END, 'scheme-approval-stages-create');
?>