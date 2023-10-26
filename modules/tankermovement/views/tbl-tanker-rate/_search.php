<?php
use yii\widgets\ActiveForm;

?>
<div class="tbl-routes-search">
    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-3 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>   
    <div class="col-sm-3 padding-right-5">
    <?= Yii::$app->dropdown->federation_union($model, $form,'union_code'); ?>
    </div>
    <div class="col-sm-2 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
