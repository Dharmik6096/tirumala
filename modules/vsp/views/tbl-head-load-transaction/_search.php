<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblHeadLoadTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-head-load-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'head_load_transaction_code') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'deleted_at') ?>

    <?= $form->field($model, 'flg_sentbox_entry') ?>

    <?= $form->field($model, 'from_km') ?>

    <?php // echo $form->field($model, 'from_qty') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'to_km') ?>

    <?php // echo $form->field($model, 'to_qty') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'head_load_code') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
