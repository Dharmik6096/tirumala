<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\reil\models\PoolingPointSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-search large-search hidden-print">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'id' => 'village-form',
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-2 padding-right-5">
        <?= $form->field($model, 'PlantCode') ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'PlantName') ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'BMCCode') ?>
    </div>
     <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'BMCName') ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'PPCode') ?>
    </div>
    <div class="col-sm-2 padding-left-0 mt25">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
