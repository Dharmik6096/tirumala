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

    <?= Yii::$app->dropdown->union($model, $form, 'tblunionconfigsearch-federation_code', 'union_code', false); ?>

    <div class="form-group col-sm-3 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
