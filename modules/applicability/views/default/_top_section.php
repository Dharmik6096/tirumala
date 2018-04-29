<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', 'Shift', false, 'shift_code'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false); ?>
</div>