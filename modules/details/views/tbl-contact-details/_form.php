<?php

use yii\helpers\Html;

$mail_info = !empty($mail_info) ? $mail_info : FALSE;
?>

<!--<div class="col-sm-3">
    <? = $form->field($model, 'contact_person')->textInput() ?>
</div>-->
<div class="col-sm-2">
    <?= $form->field($model, 'firstname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'lastname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'surname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'email')->textInput() ?>
</div>
<!--<div class="col-sm-2">
    <? = $form->field($model, 'local_contact_person')->textInput() ?>
</div>-->
<div class="col-sm-2">
    <?= $form->field($model, 'local_firstname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'local_lastname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'local_surname')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
</div>
<div class="col-sm-2">
    <?= Html::activeHiddenInput($model, 'detail_code', ['value' => $model->detail_code]) ?>
    <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
</div>
<?php if ($mail_info) { ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'email_to')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'email_cc')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'email_bcc')->textInput() ?>
    </div>
<?php } ?>

