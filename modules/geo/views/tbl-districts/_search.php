<?php

use yii\widgets\ActiveForm;

$action = Yii::$app->controller->action->id;
?>

<?php
$form = ActiveForm::begin([
            'action' => isset($actions) ? $actions : ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-8 pt5 padding_left_0">
<div class="col-sm-2">
    <?php Yii::$app->dropdown->state($model, $form, 'state_code', false); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
</div>

<?php ActiveForm::end(); ?>