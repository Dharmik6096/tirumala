<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

if ($model->isNewRecord) {
    $ids = Yii::$app->getRequest()->getQueryParam('id');
    $name = Yii::$app->getRequest()->getQueryParam('name');
} else {
    $ids = $model->village_code;
    $name = $model->villageCode->village_name;
}
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-3 mt10"><label>Village Code:</label> <?php echo $ids; ?></div>

    <div class="col-sm-3 mt10"><label>Village Name:</label> <?php echo $name; ?></div>

    <div class="col-sm-12"><hr class="hr10"></div>

    <div class="col-sm-3">
        <?= $form->field($model, 'miscellaneous_code')->dropDownList($miscellaneous, ['prompt' => 'Select Miscellaneous'])->label('Miscellaneous Name'); ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_description'); ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, str_replace(Url::base(), '', Url::previous())); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>