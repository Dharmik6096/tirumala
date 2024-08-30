<?php

use yii\widgets\ActiveForm;
?>
<div class="tbl-sub-center-search">
    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5'
                ],
                'action' => ['index-other'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no') ?>
    </div>
    <div class="col-sm-2 mt-3">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
