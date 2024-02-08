<?php

use app\components\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\usermanagement\models\User $model
 */
$this->title = yii::t('app', 'Changing password for user: ') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = yii::t('app', 'Changing password');
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
                                        '<!--<span class="fas fa-plus"></span> -->' . yii::t('app', 'Create'), ['class' => 'btn btn-default']
                                )
                                ?>
                            <?php else: ?>
                                <?=
                                Html::submitButton(
                                        '<!--<span class="glyphicon glyphicon-ok"></span> -->' . yii::t('app', 'Save'), ['class' => 'btn btn-default']
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
