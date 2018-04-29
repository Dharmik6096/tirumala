<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'bank districts');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
$bankCode = Yii::$app->getRequest()->getQueryParam('id');

?>

<div class="panel panel-main">
    <div class="panel-heading">Bank Districts for <?= $bank_name ?></div>
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
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="panel-subtitle">
                        <?php echo Yii::t('app', $title); ?>
                    </h5>
                     <?php echo $form->errorSummary($model); ?>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="btn-group">
                        <span class="input-group-btn">
                            <span id="show-only-selected-routes" class="btn btn-default">
                                <i class="fa fa-minus"></i> Show only selected
                            </span>
                            <span id="show-all-routes" class="btn btn-default hide">
                                <i class="fa fa-plus"></i> Show all
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <!--<div class="row">-->

            <?php
            $i = 0;
            foreach ($district_list as $key => $row) {
                $value = explode('-', $key);
                ?>
                <div class="row">
                    <div class="col-sm-12 dcs-city <?php echo ($i == 0) ? '' : 'mt10' ?>"><i class="fa fa-dot-circle-o"></i><b><?= $value[1] ?></b></div>
                    <?php echo Html::activeHiddenInput($model, 'bank_code[' . $i . ']', ['value' => $value[0]]) ?>

                    <?php
                    echo $form->field($model, 'district_code[' . $i . ']')->checkboxList(
                            $row, [
                        'id' => 'routes-list',
                        'item' =>
                        function ($index, $label, $name, $checked, $value) use ($selected, $bankCode, $model) {
                            $checked = in_array($value, $selected);
                            $check = $model->getDistrictUsed($bankCode, $value);
                            $disabled = ($checked && $check == 1) ? ' disabled' : '';
                            return "<div class='col-sm-4 dcs-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                        'value' => $value,
                                        'label' => $label,
                                        'labelOptions' => [
                                            'class' => 'route-text' . $disabled,
                                        ],
                                        'class' => 'route-checkbox',
                                    ]) . "</div></div>";
                        }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);
                    ?>
                </div>
                <div class="clearfix"></div>
                <?php
                $i++;
            }
            ?>

            <!--</div>-->
        </div>
    </div>
    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save($button, $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
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