<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Farmer Payment Process : Step 1';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    //'action' => ['list-payment'],
                    //'method' => 'GET',
                    'validateOnBlur' => false,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'payment_cycle')->dropDownList($paymentCycle, ['prompt' => 'Select Payment Cycle'])->label('Payment Cycle'); ?>
            </div>
        </div>
        <?= $this->render('_form_grid', [
            'model' => $model,
        ]) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

