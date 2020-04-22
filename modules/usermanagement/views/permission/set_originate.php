<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Settings for Originate Actions');
?>


<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php
$form = ActiveForm::begin([
            'id' => 'originate-form',
            'validateOnBlur' => FALSE,
            'validateOnEnter' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => FALSE,
            'validateOnSubmit' => FALSE,
        ])
?>
<div class="panel panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <div class="col-sm-12 mt15">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-th"></span> <?= Yii::t('app', 'Actions'); ?>

                    <div class="btn-group pull-right">

                        <?=
                        Html::a(
                                Yii::t('app', 'Refresh actions'), ['refresh-routes'], [
                            'class' => 'btn btn-default btn-sm',
                            'style' => 'margin-top:-3px;',
                                ]
                        )
                        ?>
                        <?=
                        Html::a(
                                Yii::t('app', 'Refresh actions (and delete unused)'), ['refresh-routes', 'deleteUnused' => 1], [
                            'class' => 'btn btn-default btn-sm',
                            'style' => 'margin-top:-3px;',
                            'data-confirm' => Yii::t('app', 'Actions that are not exists in this application will be deleted. Do not recommended for application with "advanced" structure, because frontend and backend have they own set of actions.'),
                                ]
                        )
                        ?>
                    </div>
                </div>



                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <?=
                            Html::submitButton(
                                    Yii::t('app', 'Save'), [
                                'class' => 'btn btn-default',
                                    ]
                            )
                            ?>
                        </span>

                        <input id="search-in-routes" autofocus="on" type="text" class="form-control input-sm" placeholder="<?= UserManagementModule::t('back', 'Search route'); ?>">

                    </div>

                    <hr/>
                    <div id="routes-list" class="checkbox ">
                        <?php
                        $index = 0;
                        $i = $j = $k = 0;
                        foreach ($main_menu as $menu) {
                            $model = $menu['result'];
                            $menu_text = $model->description . '-';
                            $menu_label = '<span class="route-text" style="display:none">' . $menu_text . '</span>';
                            ?>
                            <div  class="col-lg-12 ml15 cb-group">
                                <div class="form-group">
                                    <a href="#Menu-<?= $i ?>" data-toggle="collapse" data-parent="#Menu-<?= $i ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                </div>
                                <?php
                                echo Html::activeHiddenInput($model, "[$index]name");
                                echo $form->field($model, "[$index]description", ['options' => ['class' => 'form-group']])->textInput()->label($menu_label);
                                echo $form->field($model, "[$index]NATIONAL", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>NATIONAL</span>');
                                echo $form->field($model, "[$index]FEDERATION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>FEDERATION</span>');
                                echo $form->field($model, "[$index]UNION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>UNION</span>');
                                echo $form->field($model, "[$index]is_free", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>IS_FREE</span>');
                                $index++;
                                ?>

                            </div>
                            <div class="collapse in" id="Menu-<?= $i ?>">
                                <?php
                                foreach ($menu['children'] as $submenu) {
                                    $model = $submenu['result'];
                                    $submenu_text = $model->description . '-';
                                    $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . '</span>';
                                    ?>
                                    <div  class="col-lg-12 ml35 cb-group">
                                        <div class="form-group">
                                            <a href="#SubMenu-<?= $i . $j ?>" data-toggle="collapse" data-parent="#SubMenu-<?= $i . $j ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                        </div>
                                        <?php
                                        echo Html::activeHiddenInput($model, "[$index]name");
                                        echo $form->field($model, "[$index]description", ['options' => ['class' => 'form-group']])->textInput()->label($menu_label);
                                        echo $form->field($model, "[$index]NATIONAL", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>NATIONAL</span>');
                                        echo $form->field($model, "[$index]FEDERATION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>FEDERATION</span>');
                                        echo $form->field($model, "[$index]UNION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>UNION</span>');
                                        echo $form->field($model, "[$index]is_free", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>IS_FREE</span>');
                                        $index++;
                                        ?>
                                    </div>
                                    <div class="collapse in" id="SubMenu-<?= $i . $j ?>">
                                        <?php
                                        if (isset($submenu['children'])) {
                                            foreach ($submenu['children'] as $route) {
                                                if (isset($route['children'])) {
                                                    $model = $route['result'];
                                                    $route_text = $model->description . '-';
                                                    $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . $route_text . '</span>';
                                                    ?>
                                                    <div  class="col-lg-12 ml55 cb-group">
                                                        <div class="form-group">
                                                            <a href="#ChildMenu-<?= $i . $j . $k ?>" data-toggle="collapse" data-parent="#ChildMenu-<?= $i . $j . $k ?>" class="colps"><i class="fa fa-caret-down"></i></a>
                                                        </div>
                                                        <?php
                                                        echo Html::activeHiddenInput($model, "[$index]name");
                                                        echo $form->field($model, "[$index]description", ['options' => ['class' => 'form-group']])->textInput()->label($menu_label);
                                                        echo $form->field($model, "[$index]NATIONAL", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>NATIONAL</span>');
                                                        echo $form->field($model, "[$index]FEDERATION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>FEDERATION</span>');
                                                        echo $form->field($model, "[$index]UNION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>UNION</span>');
                                                        echo $form->field($model, "[$index]is_free", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>IS_FREE</span>');
                                                        $index++;
                                                        ?>
                                                    </div>
                                                    <div class="collapse in" id="ChildMenu-<?= $i . $j . $k ?>">
                                                        <?php
                                                        foreach ($route['children'] as $aciton) {
                                                            $model = $aciton['result'];
                                                            $action_text = $model->description . '-';
                                                            $menu_label = '<span class="route-text" style="display:none">' . $menu_text . $submenu_text . $route_text . $action_text . '</span>';
                                                            ?>
                                                            <div  class="col-lg-12 ml110 cb-group">
                                                                <?php
                                                                echo Html::activeHiddenInput($model, "[$index]name");
                                                                echo $form->field($model, "[$index]description", ['options' => ['class' => 'form-group']])->textInput()->label($menu_label);
                                                                echo $form->field($model, "[$index]NATIONAL", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>NATIONAL</span>');
                                                                echo $form->field($model, "[$index]FEDERATION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>FEDERATION</span>');
                                                                echo $form->field($model, "[$index]UNION", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>UNION</span>');
                                                                echo $form->field($model, "[$index]is_free", ['options' => ['class' => 'form-group']])->checkbox()->label($menu_label . '<span>IS_FREE</span>');
                                                                $index++;
                                                                ?>
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                <?php }
                                                ?>
                                                <?php
                                                $k++;
                                            }
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
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
<?php
$js = <<<JS

var routeText = $('.route-text'); 

function showAllRoutesBack() {
	$('#routes-list').find('.hide').each(function(){
		$(this).removeClass('hide');
	});
}
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
                        _t.closest('div.cb-group').removeClass('hide');
                        _t.closest('label').next().removeClass('hide');
			_t.closest('div.separator').removeClass('hide');
		}
		else
		{
			_t.closest('label').addClass('hide');
                        _t.closest('div.cb-group').addClass('hide');
                        _t.closest('label').next().addClass('hide');
			_t.closest('div.separator').addClass('hide');
		}
	});
});

JS;

$this->registerJs($js);
?>