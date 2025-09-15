<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'Organazation Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">

<div class="panel-heading">User</div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                        //                'field-class' => 'form-group col-sm-3',
                        'union_code' => 'form-group col-sm-3',
                        'milk_quality_type_code' => 'form-group col-sm-2',
                        'disease_id' => 'form-group col-sm-2',
                        'tbldcs-is_bmc' => 'form-group col-sm-3'
                    ],
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
                    'fieldConfig' => [
                    //'labelOptions' => [ 'class' => false],
        ]]);
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h5 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h5>
                </div>
                <?php echo $form->errorSummary($model); ?>
                <?php echo Html::activeHiddenInput($model, 'disease_id', ['value' => $Symtom->disease_id]) ?>
                <div class="col-sm-12 margin-top-10">
                    <div class="col-sm-2  margin-bottom-10">
                        <div class="btn-group">
                            <span class="input-group-btn">
                                <span id="show-only-selected-routes" class="btn btn-default btn-sm">
                                    <i class="fa fa-minus"></i> Show only selected
                                </span>
                                <span id="show-all-routes" class="btn btn-default hide btn-sm">
                                    <i class="fa fa-plus"></i> Show all
                                </span>
                            </span>
                        </div>
                        <div class="btn-group margin-top-10">
                                <span class="input-group-btn">
                                <span id="check-all-routes" class="btn btn-default btn-sm" data-checked ="true">
                                   <i class="fa fa-check-square facheckfalse hide"></i> <i class="fa fa-square-o fachecktrue "></i> Select All
                                </span>
                            </span>
                        </div>
                    </div>
                    <!-- <div class="col-sm-2  margin-bottom-10">
                        <div class="btn-group">
                                <span class="input-group-btn">
                                <span id="show-only-selected-routes selectallc" class="btn btn-default btn-sm">
                                   <i class="fa fa-check-square"></i> <i class="fa fa-square-o hide"></i> Select All
                                </span>
                            </span>
                        </div>
                    </div> -->
                    <div class="col-sm-2 ">
                    </div>
                    <?php
                    $i = 0;
//             var_dump($product_groups);
//                exit;
                    //foreach ($product_groups as $key => $row) {
                    ?>
                    <div class="col-sm-12">
                        <?php
                        echo $form->field($model, 'symptom_id')->checkboxList(
                                $destinations, [
                            'id' => 'routes-list',
                            'class' => 'row mb15',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) use ($selected, $defaultValue, $Symtom) {
                                $checked = in_array($value, $selected);
                                $Symtom->disease_id = Yii::$app->getRequest()->getQueryParam('id');
                                $disabled = $checked ? ' disabled' : '';
                                return "<div class='col-sm-2 checklist dcs-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                            'value' => $value,
                                            'label' => '<label for=' . $value . '>' . $label . '</label>',
                                            'labelOptions' => [
                                                'class' => 'route-text' . $disabled,
                                            ],
                                            'class' => 'route-checkbox',
                                            'id' => $value,
                                        ]) . "</div></div>";
                            }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);
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
                'Symtom' => $Symtom,
            ])
            ?>
        </div>
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

$('#check-all-routes').on('click', function(){
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
   
	$('.route-checkbox').each(function(){
        var _t2 = $(this);
        var isChecked = _t2.is(':checked');
        _t2.prop('checked', checkedAll == 'true' ? true : false);
        _t2.closest('label').css('background', checkedAll == 'true' ? backgroundColor : 'none' );
        
    });
})

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