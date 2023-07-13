<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\usermanagement\models\User $model
 */
$this->title = UserManagementModule::t('back', 'Changing password for user: ') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = UserManagementModule::t('back', 'Changing password');
?>
<div class="user-update">

    <div class="panel panel-main">
        <div class="panel-heading"><?= $this->title ?></div>
        <div class="panel-body">

            <div class="user-form">

                <div class="panel-subheading">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'user',
//                            'layout' => 'horizontal',
                    ]);
                    ?>

                    <div class="row">
                        <div class="col-sm-3">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                        </div>

                        <div class="clearfix"></div>
                        
                        <div class="col-sm-12">
                            <?php if ($model->isNewRecord): ?>
                                <?=
                                Html::submitButton(
                                        '<!--<span class="fas fa-plus"></span> -->' . UserManagementModule::t('back', 'Create'), ['class' => 'btn btn-default']
                                )
                                ?>
                            <?php else: ?>
                                <?=
                                Html::submitButton(
                                        '<!--<span class="glyphicon glyphicon-ok"></span> -->' . UserManagementModule::t('back', 'Save'), ['class' => 'btn btn-default']
                                )
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
