<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup $model
 * @var yii\bootstrap\ActiveForm $form
 */
$title = Yii::$app->label->title($type, 'permission group');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'auth-item-group-form',
            'validateOnBlur' => false,
        ]);
?>

<div class="row">
    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autofocus' => $model->isNewRecord ? true : false]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'code')->textInput(['maxlength' => 64, 'readonly' => $readonly]) ?>
    </div>
    
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>