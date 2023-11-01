<?php

use yii\helpers\Html;
?>
<div class="col-sm-2">
    <?= $form->field($model, 'owner_name')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdownStatic('chiller_rate_type', $model, $form, 'form-group', $model->getAttributeLabel('rate_type'), false, 'rate_type', false); ?>
</div>
<div class="col-sm-2 number-validate">
    <?= $form->field($model, 'chilling_capacity')->textInput() ?>
</div>
<div class="col-sm-2 number-validate">
    <?= $form->field($model, 'min_qty')->textInput() ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'pan_no')->textInput() ?>
</div>
<div class="col-sm-2 number-validate">
    <?= $form->field($model, 'tds_percentage')->textInput() ?>
</div>
<div class="col-sm-2 filldata">
    <?= Yii::$app->controls->date($model, $form, 'installation_date', '', date('Y-m-d'), false, FALSE, true); ?>
</div>
<div class="col-sm-2">
    <?= $form->field($model, 'agreement_no')->textInput() ?>
</div>
<div class="col-sm-2 filldata">
    <?= Yii::$app->controls->date($model, $form, 'agreement_from_date', '', date('Y-m-d'), false, FALSE, true); ?>
</div>
<div class="col-sm-2 filldata">
    <?= Yii::$app->controls->date($model, $form, 'agreement_to_date', '', date('Y-m-d'), false, FALSE, true); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdownStatic('boolean_value', $model, $form, 'form-group', $model->getAttributeLabel('is_active'), false, 'is_active', false); ?>
</div>
<?= Html::activeHiddenInput($model, 'chiller_info_code', ['value' => $model->chiller_info_code]) ?>