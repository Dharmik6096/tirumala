<?php

use yii\helpers\Html;
?>
<div class="col-sm-2">
    <?= $form->field($model, 'family_member_name')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'local_family_member_name')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdown('relation', $model, $form, '', $model->getAttributeLabel('relation_code'), false, 'relation_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($model, $form, 'birth_date', '', true); ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'age')->textInput(['readonly' => true]) ?>
</div>

<div class="col-sm-2">
    <?= Html::activeHiddenInput($model, 'staff_family_details_code', ['id' => 'tblstaffmemberfamilydetails-staff_family_details_code']) ?>
    <?= Yii::$app->dropdown->dropdown('gender', $model, $form, '', 'Gender'); ?>
</div>
<div class="col-sm-2 mt5">
    <?= $form->field($model, 'is_nominee', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
</div>

