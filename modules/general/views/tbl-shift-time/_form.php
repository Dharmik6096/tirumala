<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblShiftTime */
/* @var $form yii\widgets\ActiveForm */
isset($model->dcsCode) ? $model->union_code = $model->dcsCode->union_code : $model->union_code = $model->union_code;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblshifttime-union_code', '', 'Society'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', 'Shift', false, 'shift_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'start_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',])->label('Start Time (24 Hrs)');
        ?>
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'end_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',])->label('End Time (24 Hrs)')
        ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'allow_after_collection', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>


<?php ActiveForm::end();
