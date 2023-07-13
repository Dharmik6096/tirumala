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

$this->title = Yii::t('app', 'Settings for permission') . ': ' . ($item->group_code ? $item->group->name : '');
?>


<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>


<div class="panel panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <div class="col-sm-12 mt15">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-th"></span> <?= Yii::t('app', 'Actions'); ?>

                </div>

                <?= Html::beginForm(['set-child-routes', 'id' => $item->name]) ?>

                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <?=
                            Html::submitButton(
                                    Yii::t('app', 'Save'), ['class' => 'btn btn-default']
                            )
                            ?>
                        </span>

                        <input id="search-in-routes" autofocus="on" type="text" class="form-control input-sm" placeholder="<?= UserManagementModule::t('back', 'Search route'); ?>">

                        <span class="input-group-btn">
                            <span id="show-only-selected-routes" class="btn btn-default">
                                <i class="fa fa-minus"></i> <?= Yii::t('app', 'Show only selected'); ?>
                            </span>

                            <span id="show-all-routes" class="btn btn-default hide">
                                <i class="fa fa-plus"></i> <?= Yii::t('app', 'Show all'); ?>
                            </span>
                        </span>
                    </div>

                    <hr/>
                    <div id="routes-list" class="checkbox">
                        <?php
                        $i = $j = $k = 0;
                        foreach ($main_menu as $menu) {
                            $class_menu = str_replace('/', '', trim($menu['name']));
                            $menu_text = $menu['description'] . '-';
                            $menu_label = '<span class="route-text" style="display:none">' . $menu_text . '</span>';
                            ?>
                            <div  class="col-lg-12 ml15">
                                <?=
                                Html::checkbox('child_routes[]', array_key_exists($menu['name'], $childRoutes), [
                                    'label' => $menu_label . '<span>' . $menu['description'] . '</span>',
                                    'value' => $menu['name'], 'class' => 'route-checkbox checkAll'
                                ])
                                ?>
                                <a href="#Menu-<?= $i ?>" data-toggle="collapse" data-parent="#Menu-<?= $i ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                            </div>
                            <div class="collapse in" id="Menu-<?= $i ?>">
                                <?php
                                foreach ($menu['children'] as $submenu) {
                                    $class_submenu = str_replace('/', '', trim($submenu['name']));
                                    $submenu_text = $submenu['description'] . '-';
                                    $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . '</span>';
                                    ?>
                                    <div  class="col-lg-12 ml35">
                                        <?=
                                        Html::checkbox('child_routes[]', array_key_exists($submenu['name'], $childRoutes), [
                                            'label' => $menu_label . '<span>' . $submenu['description'] . '</span>',
                                            'value' => $submenu['name'], 'class' => 'route-checkbox checkAll ' . $class_menu]);
                                        ?>
                                        <a href="#SubMenu-<?= $i . $j ?>" data-toggle="collapse" data-parent="#SubMenu-<?= $i . $j ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                    </div>
                                    <div class="collapse in" id="SubMenu-<?= $i . $j ?>">
                                        <?php
                                        foreach ($submenu['children'] as $route) {
                                            if (!empty($route['children'])) {
                                                $class_route = str_replace('/', '', trim($route['name']));
                                                $route_text = $route['description'] . '-';
                                                $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . $route_text . '</span>';
                                                ?>
                                                <div  class="col-lg-12 ml55">
                                                    <?=
                                                    Html::checkbox('child_routes[]', array_key_exists($route['name'], $childRoutes), [
                                                        'label' => $menu_label . '<span>' . $route['description'] . '</span>',
                                                        'value' => $route['name'], 'class' => 'route-checkbox checkAll ' . $class_submenu . ' ' . $class_menu])
                                                    ?>
                                                    <a href="#ChildMenu-<?= $i . $j . $k ?>" data-toggle="collapse" data-parent="#ChildMenu-<?= $i . $j . $k ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                                </div>
                                                <div class="collapse in" id="ChildMenu-<?= $i . $j . $k ?>">
                                                    <div class="col-lg-12 ml85">                                           
                                                        <?php
                                                        $action = $route['children'];
                                                        echo Html::checkboxList(
                                                                'child_routes[]', ArrayHelper::map($childRoutes, 'name', 'name'), ArrayHelper::map($action, 'name', 'name'), [
                                                            'id' => 'routes-list',
                                                            'class' => 'checkbox',
                                                            'separator' => '<div class="separator"></div>',
                                                            'item' => function($index, $label, $name, $checked, $value)use ($action, $class_submenu, $class_menu, $class_route, $menu_text, $submenu_text, $route_text) {
                                                                if (!isset($action[$value])) {
                                                                    return;
                                                                }
                                                                $action_text = $action[$value]['description'] . '-';
                                                                $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . $route_text . $action_text . '</span>';

                                                                return Html::checkbox('child_routes[]', $checked, [
                                                                            'label' => $menu_label . '<span>' . $action[$value]['description'] . '</span>',
                                                                            'value' => $action[$value]['name'], 'class' => 'route-checkbox ' . $class_submenu . ' ' . $class_menu . ' ' . $class_route]);
                                                            },
                                                                        ]
                                                                );
                                                                ?>
                                                            </div> 
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <div class="col-lg-12 ml55">
                                                            <?=
                                                            Html::checkboxList(
                                                                    'child_routes[]', ArrayHelper::map($childRoutes, 'name', 'name'), ArrayHelper::map($route, 'name', 'name'), [
                                                                'id' => 'routes-list',
                                                                'class' => 'checkbox',
                                                                'separator' => '<div class="separator"></div>',
                                                                'item' => function($index, $label, $name, $checked, $value)use ($route, $class_submenu, $class_menu, $menu_text, $submenu_text) {
                                                                    if (!isset($route[$value])) {
                                                                        return;
                                                                    }
                                                                    $route_text = $route[$value]['description'] . '-';
                                                                    $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . $route_text . '</span>';

                                                                    return Html::checkbox('child_routes[]', $checked, [
                                                                                'label' => $menu_label . '<span>' . $route[$value]['description'] . '</span>',
                                                                                'value' => $route[$value]['name'], 'class' => 'route-checkbox ' . $class_submenu . ' ' . $class_menu]);
                                                                },
                                                                            ]
                                                                    )
                                                                    ?> 
                                                                </div>
                                                            <?php } ?>

                                                            <?php
                                                            $k++;
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                    $j++;
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="true">
                                    <?=
                                    Html::submitButton(
                                            Yii::t('app', 'save'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+s']
                                    )
                                    ?>
                                    <?php echo Html::a('back', ['index'], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
                                </div>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>

                </div>

                <?php
                $js = <<<JS
// For checked routes
var backgroundColor = '#D6FFDE'; 
var routeCheckboxes = $('.route-checkbox');
var routeText = $('.route-text'); 
        
     $('.checkAll').click(function(event) {
        var str = this.value;
        var str = str.replace((new RegExp('/', 'g')), "");
        var str = str.replace(" ", "");
         $('.'+str).prop('checked', this.checked); 
        if(this.checked){
         $('.'+str).closest('label').css('background', backgroundColor);
        }else{
       $('.'+str).closest('label').css('background', 'none');
        }
        });          
        
function showAllRoutesBack() {
	$('#routes-list').find('.hide').each(function(){
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
			_t.closest('label').addClass('hide');
                        _t.closest('label').next().addClass('hide');
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
                        _t.closest('label').next().removeClass('hide');
			_t.closest('div.separator').removeClass('hide');
                         _t.closest('div.col-lg-12').removeClass('hide');
		}
		else
		{
			_t.closest('label').addClass('hide');
                        _t.closest('label').next().addClass('hide');
			_t.closest('div.separator').addClass('hide');
                        _t.closest('div.col-lg-12').addClass('hide');
		}
	});
});

JS;

                $this->registerJs($js);
                ?>