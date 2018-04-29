<?php

use yii\widgets\ActiveForm;
use yii\web\View;

?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>

<div class="col-sm-3" id="union">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblshifttimesearch-union_code', ''); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift_code'); ?>
</div> 
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>