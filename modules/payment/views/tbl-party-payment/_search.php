<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<?php
$form = ActiveForm::begin([
            'method' => 'get',
        ]);
?>   

<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, true); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, true); ?>
</div>

<div class="form-group padding_top_20">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
