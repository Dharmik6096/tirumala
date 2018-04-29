<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly=$type=='create'?FALSE:TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union',$readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('product_group_code', $model, $form, '', 'Product Group', false, 'product_group_code'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'product_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'description')->textarea() ?>
    </div>
     <div class="col-sm-3 mt35">
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
<?php ActiveForm::end(); ?>