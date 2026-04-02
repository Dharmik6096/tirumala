<?php

use yii\helpers\Html;

$mail_info = !empty($mail_info) ? $mail_info : FALSE;
$show_optional_fields = !empty($show_optional_fields) ? $show_optional_fields : FALSE;
$plant_mail_info = !empty($plant_mail_info) ? $plant_mail_info : FALSE;
?>

<!--<div class="col-sm-3">
    <? // $form->field($model, 'contact_person')->textInput() ?>
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
    <? // $form->field($model, 'local_contact_person')->textInput() ?>
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
<?php } else if ($plant_mail_info) { ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'email_to')->textInput() ?>
    </div>
<?php } ?>
<?php if ($show_optional_fields) { ?>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('user', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('primary_parent'), false, 'primary_parent'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('user', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('secondary_parent'), false, 'secondary_parent'); ?>
    </div>
<?php } ?>
