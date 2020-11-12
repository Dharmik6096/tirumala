<?php

use yii\widgets\ActiveForm;
?>


<?php
$form = ActiveForm::begin([
            'action' => isset($actions) ? $actions : ['index'],
            'method' => 'get',
        ]);
?>

<div class="col-sm-8 pt5 padding_left_0">
<div class="col-sm-2">
    <?php Yii::$app->dropdown->state($model, $form, 'state', false); ?>
</div>

<div class="col-sm-2">
    <?php Yii::$app->dropdown->district($model, $form, 'tblblockssearch-state', 'district'); ?>
</div>
    
<div class="col-sm-2">
    <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblblockssearch-district', false); ?>
</div>

<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
</div>

<?php ActiveForm::end(); ?>