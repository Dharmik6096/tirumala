<?php

use yii\widgets\ActiveForm;

?>

<div class="tbl-sub-center-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-3 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?php Yii::$app->dropdown->federation($model, $form, 'federation_code',false); ?>

    <?= Yii::$app->dropdown->union($model, $form, 'tblmeetingagendasearch-federation_code', 'union_code', false); ?>
    
    <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblmeetingagendasearch-union_code'); ?>    

    <div class="form-group col-sm-3 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
