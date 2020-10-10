<?php

use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'action' => isset($actions) ? $actions : ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-8 pt5">
<div class="col-sm-2">
    <?php Yii::$app->dropdown->state($model, $form, 'state', false); ?>
</div>
<?php //Yii::$app->dropdown->depend_dropdown('district_code',$model, $form, 'tblsubdistrictssearch-state','form-group col-sm-2 padding-right-5 padding-left-0',false,'district_code');  ?>
<div class="col-sm-2">
    <?php Yii::$app->dropdown->district($model, $form, 'tblsubdistrictssearch-state'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
</div>
<?php ActiveForm::end(); ?>