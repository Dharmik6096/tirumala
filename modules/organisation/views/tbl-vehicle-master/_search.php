<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
    <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehiclemastersearch-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0'); ?>
</div>

<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
