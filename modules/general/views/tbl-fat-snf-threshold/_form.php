<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblFatSnfThreshold */
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

<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblfatsnfthreshold-union_code', '', 'Society'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', 'Shift', false, 'shift_id'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'minimum_fat')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'maximum_fat')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'minimum_snf')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'maximum_snf')->textInput() ?>
    </div>
    <div class="col-sm-3">
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