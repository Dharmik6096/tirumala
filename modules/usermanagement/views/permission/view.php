<?php

/**
 * @var $this yii\web\View
 * @var yii\widgets\ActiveForm $form
 * @var array $routes
 * @var array $childRoutes
 * @var array $permissionsByGroup
 * @var array $childPermissions
 * @var yii\rbac\Permission $item
 */
use app\modules\usermanagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//echo '<pre>';
//print_r($item);exit;
$this->title = UserManagementModule::t('back', 'Settings for permission') . ': ' . $item->description;
//$this->title = UserManagementModule::t('back', 'Settings for permission') . ': ' . ($item->group_code ? $item->group->name : '');
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//$this->params['menu'][] = Yii::$app->controls->update($item->group_code);
?>


<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>


<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($item); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                    <h4 class="theme-box-heading">Routes</h4>
                </div>
                <div class="btn-group pull-right">
                    <?=
                    Html::a(
                            UserManagementModule::t('back', 'Refresh routes'), ['refresh-routes', 'id' => $item->name], [
                        'class' => 'btn btn-default btn-sm',
                        'style' => 'margin-top:-3px;',
                            ]
                    )
                    ?>
                    <?=
                    Html::a(
                            UserManagementModule::t('back', 'Refresh routes (and delete unused)'), ['refresh-routes', 'id' => $item->name, 'deleteUnused' => 1], [
                        'class' => 'btn btn-default btn-sm',
                        'style' => 'margin-top:-3px;',
                        'data-confirm' => UserManagementModule::t('back', 'Routes that are not exists in this application will be deleted. Do not recommended for application with "advanced" structure, because frontend and backend have they own set of routes.'),
                            ]
                    )
                    ?>
                </div>

            <?= Html::beginForm(['set-child-routes', 'id' => $item->name]) ?>

            <div class="col-sm-12 mt10">
                <div class="input-group">
                    <span class="input-group-btn">
                        <?=
                        Html::submitButton(
                                '<!--<span class="glyphicon glyphicon-ok"></span> -->' . UserManagementModule::t('back', 'Save'), ['class' => 'btn btn-default']
                        )
                        ?>
                    </span>

                    <input id="search-in-routes" autofocus="on" type="text" class="form-control input-sm" placeholder="<?= UserManagementModule::t('back', 'Search route'); ?>">

                    <span class="input-group-btn">
                        <span id="show-only-selected-routes" class="btn btn-default">
                            <i class="fa fa-minus"></i> <?= UserManagementModule::t('back', 'Show only selected'); ?>
                        </span>

                        <span id="show-all-routes" class="btn btn-default hide">
                            <i class="fa fa-plus"></i> <?= UserManagementModule::t('back', 'Show all'); ?>
                        </span>
                    </span>
                </div>
            </div>

            <hr/>

            <div class="col-sm-12">
                <?=
                Html::checkboxList(
                        'child_routes', ArrayHelper::map($childRoutes, 'name', 'name'), ArrayHelper::map($routes, 'name', 'description'), [
                    'id' => 'routes-list',
                    'class' => 'checkbox',
                    'separator' => '<div class="separator"></div>',
                    'item' => function($index, $label, $name, $checked, $value)use ($routes) {
                        return Html::checkbox($name, $checked, [
                                    'value' => $value,
                                    'label' => '<span for="' . $value . '">' . $routes[$index]['description'] . '</span><span class="route-text">' . $label . '-' . $value . '</span>',
                                    'labelOptions' => ['class' => 'route-label mt10'],
                                    'class' => 'route-checkbox',
                                    'id' => $value,
                        ]);
                    },
                                ]
                        )
                        ?>
                    </div>

                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="true">
                        <div class="form-group">
                            <?=
                            Html::submitButton(
                                    UserManagementModule::t('back', 'save'), ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+s']
                            )
                            ?>
                            <?php echo Html::a('cancel', ['create'], ['class' => 'btn btn-danger apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
                        </div>
                    </div>
                    <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $js = <<<JS

var routeCheckboxes = $('.route-checkbox');
var routeText = $('.route-text');

// For checked routes
var backgroundColor = '#D6FFDE';

function showAllRoutesBack() {
	$('#routes-list').find('.hide').each(function(){
		$(this).removeClass('hide');
	});
}

//Make tree-like structure by padding controllers and actions
routeText.each(function(){
	var _t = $(this);

	var chunks = _t.html().split('/').reverse();
	var margin = chunks.length * 40 - 40;

	if ( chunks[0] == '*' )
	{
		margin -= 40;
	}

	_t.closest('label').css('margin-left', margin);

});

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
			_t.closest('label').addClass('hide');
			_t.closest('div.separator').addClass('hide');
		}
	});
});

// Show all routes back
$('#show-all-routes').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-routes').removeClass('hide');

	showAllRoutesBack();
});

// Search in routes and hide not matched
$('#search-in-routes').on('change keyup', function(){
	var input = $(this);

	if ( input.val() == '' )
	{
		showAllRoutesBack();
		return;
	}

	routeText.each(function(){
		var _t = $(this);

                if ( _t.html().toLowerCase().indexOf(input.val().toLowerCase()) > -1 )
		{
			_t.closest('label').removeClass('hide');
			_t.closest('div.separator').removeClass('hide');
		}
		else
		{
			_t.closest('label').addClass('hide');
			_t.closest('div.separator').addClass('hide');
		}
	});
});

JS;

        $this->registerJs($js);
        ?>