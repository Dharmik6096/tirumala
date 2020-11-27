<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', 'Shift', false, 'shift_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false); ?>
</div>