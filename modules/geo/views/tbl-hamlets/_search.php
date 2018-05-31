<?php

use yii\widgets\ActiveForm;
?>


<?php
$form = ActiveForm::begin([
            'action' => isset($actions) ? $actions : ['index'],
            'method' => 'get',
        ]);
?>

<div class="col-sm-2">
    <?php Yii::$app->dropdown->state($model, $form, 'state', false); ?>
</div>

<div class="col-sm-2">
    <?php Yii::$app->dropdown->district($model, $form, 'tblhamletssearch-state', 'district'); ?>
</div>

<?php //Yii::$app->dropdown->depend_dropdown('district_code',$model, $form, 'tblhamletssearch-state','form-group col-sm-2 padding-right-5 padding-left-0',false,'district'); ?>
<?php //Yii::$app->dropdown->subDistric($model, $form, 'tblhamletssearch-district', 'sub_district'); ?>

<div class="col-sm-2">
    <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblhamletssearch-district', '', false, 'sub_district'); ?>
</div>

<?php //Yii::$app->dropdown->village($model, $form, 'tblhamletssearch-sub_district', 'village_code'); ?>

<div class="col-sm-2">
    <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblhamletssearch-sub_district', false); ?>
</div>

<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>

<?php ActiveForm::end(); ?>