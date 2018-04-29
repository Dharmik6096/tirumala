<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$title = Yii::$app->label->title($type, 'Bloodgroup');
$button = Yii::$app->label->button($type);

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblQualification */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'qualification_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'sequences_no')->textInput() ?>
    </div>
    <div class="col-sm-3 mt35">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

