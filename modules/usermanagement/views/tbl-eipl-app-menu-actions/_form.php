
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
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>



<div class="showHideData">
    <div class="col-sm-12 mt15">
        <div class="panel panel-default">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'widget-mapping-form',
            ]);
            ?>     
            <?= Html::hiddenInput('union_code', $mappingModel->union_code, ['id' => 'union_code']); ?>
            <?= Html::hiddenInput('login_type', $mappingModel->login_type, ['id' => 'login_type']); ?>
            <?= Html::hiddenInput('department', $mappingModel->department, ['id' => 'department']); ?>
            <?= Html::hiddenInput('app_type', $mappingModel->app_type, ['id' => 'app_type']); ?>

            <div class="panel-body">
                <div class="col-md-12 padding_10_0 theme-box">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                        <h4 class="theme-box-heading">Menu Permission</h4>
                    </div>
                    <div class="col-sm-12 col-md-12 margin-bottom-10 clearfix">
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
                    </div>
                    <hr/>
                    <div id="routes-list" class="checkbox">
                        <?php
                        $i = $j = $k = $l = 0;
                        foreach ($menuArray as $menu) {
                            $class_menu = str_replace('/', '', trim($menu['action_code']));
                            $menu_text = $menu['description'];
                            $menu_label = '<span class="route-text" style="display:none">' . $menu_text . '</span>';
                            ?>
                            <div  class="col-lg-12 ml15">
                                <?=
                                Html::checkbox('child_routes[]', in_array($menu['action_code'], $selectedArray), [
                                    'label' => $menu_label . '<span>' . $menu['description'] . '</span>',
                                    'value' => $menu['action_code'], 'class' => 'route-checkbox checkAll'
                                ])
                                ?>
                                <?php if (!empty($menu['children'])) { ?>
                                    <a href="#Menu-<?= $i ?>" data-toggle="collapse" data-parent="#Menu-<?= $i ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                <?php } ?>
                            </div>
                            <div class="collapse in" id="Menu-<?= $i ?>">
                                <?php
                                if (isset($menu['children'])) {
                                    foreach ($menu['children'] as $submenu) {
                                        $class_submenu = str_replace('/', '', trim($submenu['action_code']));
                                        $submenu_text = $submenu['description'];
                                        $menu_label = '<span class="route-text" style="display:none">' . $menu_text . ' : ' . $submenu_text . '</span>';
                                        ?>
                                        <div  class="col-lg-12 ml35">
                                            <?=
                                            Html::checkbox('child_routes[]', in_array($submenu['action_code'], $selectedArray), [
                                                'label' => $menu_label . '<span>' . $submenu['description'] . '</span>',
                                                'value' => $submenu['action_code'], 'class' => 'route-checkbox checkAll ' . $class_menu]);
                                            ?>
                                            <?php if (!empty($submenu['children'])) { ?>
                                                <a href="#SubMenu-<?= $i . $j ?>" data-toggle="collapse" data-parent="#SubMenu-<?= $i . $j ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                            <?php } ?>
                                        </div>
                                        <div class="collapse in" id="SubMenu-<?= $i . $j ?>">
                                            <?php
                                            if (isset($submenu['children'])) {
                                                foreach ($submenu['children'] as $route) {
                                                    $class_route = str_replace('/', '', trim($route['action_code']));
                                                    $route_text = $route['description'];
                                                    $menu_label = '<span class="route-text" style="display:none">' . $submenu_text . ' : ' . $route_text . '</span>';
                                                    ?>
                                                    <div  class="col-lg-12 ml55">
                                                        <?=
                                                        Html::checkbox('child_routes[]', in_array($route['action_code'], $selectedArray), [
                                                            'label' => $menu_label . '<span>' . $route['description'] . '</span>',
                                                            'value' => $route['action_code'], 'class' => 'route-checkbox checkAll ' . $class_submenu . ' ' . $class_menu])
                                                        ?>
                                                        <?php if (!empty($route['children'])) { ?>
                                                            <a href="#ChildMenu-<?= $i . $j . $k ?>" data-toggle="collapse" data-parent="#ChildMenu-<?= $i . $j . $k ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="collapse in" id="ChildMenu-<?= $i . $j . $k ?>">
                                                        <?php
                                                        if (isset($route['children'])) {
                                                            foreach ($route['children'] as $route_sub) {
                                                                $class_route_sub = str_replace('/', '', trim($route_sub['action_code']));
                                                                $route_sub_text = $route_sub['description'];
                                                                $menu_label = '<span class="route-text" style="display:none">' . $route_text . ' : ' . $route_sub_text . '</span>';
                                                                ?>
                                                                <div  class="col-lg-12 ml85">
                                                                    <?=
                                                                    Html::checkbox('child_routes[]', in_array($route_sub['action_code'], $selectedArray), [
                                                                        'label' => $menu_label . '<span>' . $route_sub['description'] . '</span>',
                                                                        'value' => $route_sub['action_code'], 'class' => 'route-checkbox checkAll ' . $class_route . ' ' . $class_submenu . ' ' . $class_menu])
                                                                    ?>
                                                                    <?php if (!empty($route_sub['children'])) { ?>
                                                                        <a href="#SubChildMenu-<?= $i . $j . $k . $l ?>" data-toggle="collapse" data-parent="#SubChildMenu-<?= $i . $j . $k . $l ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                                                    <?php } ?>
                                                                </div>
                                                                <?php
                                                                $l++;
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                    $k++;
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $j++;
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>

                <div class="panel-footer">
                    <?php
                    echo GhostHtml::a_alert(Yii::t('app', 'Save'), ['/usermanagement/tbl-eipl-app-menu-actions/app-menu-mapping'], ['class' => 'btn btn-primary', 'id' => 'mapping-widget']);
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
    $("#mapping-widget").click(function() {
        $("#widget-mapping-form").submit();
    });
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
