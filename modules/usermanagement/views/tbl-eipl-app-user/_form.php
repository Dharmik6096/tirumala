<?php

use app\components\ActiveForm;

$title = Yii::$app->label->title($type, 'Eipl App User');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readOnly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'user-form',
            'validateOnBlur' => false,
        ])
?>

<div class="row">
    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_length_with_mex']) ?>
    </div>
    <div class="col-sm-2 user_type_show">
        <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
    </div>
</div>
<div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save($button, $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>
</div>
<?php ActiveForm::end() ?>
