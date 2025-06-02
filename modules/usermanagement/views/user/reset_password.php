<?php

use app\components\ActiveForm;
use yii\helpers\Html;
?>
<div class="panel panel-main">
    <div class="modal-header">
        <h4 class="modal-title"><?= $this->title = Yii::t('app', 'Changing password for user : ') . $model->username ?></h4>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'user',
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row margin-top-10">
            <div class="col-sm-3">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'class' => 'form-control check_password_strength']) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
