<?php

/**
 * @var yii\widgets\ActiveForm $form
 * @var array $childRoles
 * @var array $allRoles
 * @var array $routes
 * @var array $currentRoutes
 * @var array $permissionsByGroup
 * @var array $currentPermissions
 * @var yii\rbac\Role $role
 */
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = UserManagementModule::t('back', 'Permissions for role:') . ' ' . $role->description;
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'][]=Yii::$app->controls->update($role->description);
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($role); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">

        <?= Html::beginForm(['set-child-permissions', 'id' => $role->name]) ?>
        <div class="row">
            <?php foreach ($permissionsByGroup as $groupName => $permissions): ?>
                <div class="col-md-6 padding_10_0 theme-box view-subtitle">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                        <h4 class="theme-box-heading"><?= $groupName ?></h4>
                    </div>
                    <div class="app-check-list-padding">
                        <?php foreach ($permissions as $permission): ?>
                            <div class="checkbox">
                                <label>
                                    <?php $isChecked = in_array($permission->name, ArrayHelper::map($currentPermissions, 'name', 'name')) ? 'checked' : '' ?>
                                    <input type="checkbox" <?= $isChecked ?> name="child_permissions[]" id="<?= $permission->name ?>" value="<?= $permission->name ?>">
                                    <span for="<?= $permission->name ?>"><?= $permission->description ?></span>
                                </label>

                                <?=
                                GhostHtml::a(
                                        '<i class="fa fa-pencil-square-o"></i>', ['/user-management/permission/view', 'id' => $permission->name], ['target' => '_blank', 'title' => 'Edit']
                                )
                                ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>

            <div class="clearfix"></div>
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
        </div>
        <?= Html::endForm() ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS

$('.role-help-btn').off('mouseover mouseleave')
	.on('mouseover', function(){
		var _t = $(this);
		_t.popover('show');
	}).on('mouseleave', function(){
		var _t = $(this);
		_t.popover('hide');
	});
JS
);
?>