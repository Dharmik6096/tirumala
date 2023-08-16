<?php

/**
 * @var yii\web\View $this
 * @var array $permissionsByGroup
 * @var app\modules\usermanagement\models\User $user
 */
use app\modules\usermanagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);
$this->title = UserManagementModule::t('back', 'Roles and permissions for user:') . ' ' . $user->username;

$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['/user-management/user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?php echo Html::a('<i class="fa fa-arrow-left"></i>', Yii::$app->request->referrer, ["class" => "btn btn-primary"]); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">

        <?= Html::beginForm(['set-roles', 'id' => $user->id]) ?>
        <div class="row">
            <div class="col-sm-12">
                <?php foreach (Role::getAvailableRoles(true) as $aRole): ?>
                    <div class="checkbox">
                        <label>
                            <?php $isChecked = in_array($aRole['name'], ArrayHelper::map(Role::getUserRoles($user->id), 'name', 'name')) ? 'checked' : '' ?>

                            <?php if (Yii::$app->getModule('user-management')->userCanHaveMultipleRoles): ?>
                                <input type="checkbox" <?= $isChecked ?> name="roles[]" id="<?= $aRole['name'] ?>" value="<?= $aRole['name'] ?>">

                            <?php else: ?>
                                <input type="radio" <?= $isChecked ?> name="roles" value="<?= $aRole['name'] ?>">

                            <?php endif; ?>

                            <span for="<?= $aRole['name'] ?>"><?= $aRole['description'] ?></span>
                        </label>

                        <?=
                        GhostHtml::a(
                                '<i class="fa fa-pencil-alt-sqare-o"></i>', ['/user-management/role/view', 'id' => $aRole['name']], ['target' => '_blank', 'title' => 'Edit']
                        )
                        ?>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="true">
                <?php if (Yii::$app->user->isSuperadmin OR Yii::$app->user->id != $user->id): ?>
                    <?=
                    Html::submitButton(
                            UserManagementModule::t('back', 'save'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+s']
                    )
                    ?>
                <?php else: ?>
                    <div class="alert alert-warning well-sm text-center">
                        <?= UserManagementModule::t('back', 'You can not change own permissions') ?>
                    </div>
                <?php endif; ?>
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