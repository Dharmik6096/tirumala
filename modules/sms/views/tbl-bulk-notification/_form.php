<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$title = Yii::$app->label->title($type, 'Bulk Notification');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'role-form',
            'validateOnBlur' => false,
        ])
?>

<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('receiver_type', $model, $form, '', $model->getAttributeLabel('receiver_type'), false, 'receiver_type', false); ?>  
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('user_login_type', $model, $form, '', $model->getAttributeLabel('login_type'), false, 'login_type', false); ?>  
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false, date('Y-m-d'), false, true); ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'message')->textarea(['maxlength' => 255]) ?>
    </div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
