<?php

use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-8 pt5">
<div class="col-sm-2">
<?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
</div>
<div class="col-sm-2">
<?= Yii::$app->controls->search(); ?>
</div>
</div>
<?php ActiveForm::end(); ?>