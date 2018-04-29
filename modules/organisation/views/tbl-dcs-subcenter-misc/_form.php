<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsMiscellaneous */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, Yii::$app->getRequest()->getQueryParam('type') . ' miscellaneous');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3 mt10"><label><?php // Yii::$app->getRequest()->getQueryParam('type') ?>Society Code:</label> <?php echo Yii::$app->getRequest()->getQueryParam('id'); ?></div>

    <div class="col-sm-3 mt10"><label><?php // Yii::$app->getRequest()->getQueryParam('type') ?>Society Name:</label> <?php echo Yii::$app->getRequest()->getQueryParam('name'); ?></div>

    <div class="col-sm-12"><hr class="hr10"></div>

    <div class="col-sm-3">
        <?= $form->field($model, 'miscellaneous_code')->dropDownList($miscellaneous, ['prompt' => 'Select Miscellaneous']); ?>
    </div>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, str_replace(Url::base(), '', Url::previous())); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

