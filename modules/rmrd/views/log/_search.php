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
                'id' => 'pooling-point-form',
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-2 padding-right-5">
        <?= $form->field($model, 'Id') ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'Date') ?>
        <!--<? = Yii::$app->controls->date($model, $form, 'Date', '', FALSE, date('Y-m-d')); ?>-->
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'Thread') ?>
    </div>
     <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'Level') ?>
    </div>
    <div class="col-sm-2 padding-left-0 padding-right-5">
        <?= $form->field($model, 'Logger') ?>
    </div>
    <div class="col-sm-2 padding-left-0 mt25">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
