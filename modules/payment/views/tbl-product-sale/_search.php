<?php

use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'options' => [
                'field-class' => 'form-group col-sm-3 padding-right-5'
            ],
            'action' => ['index'],
            'method' => 'get',
        ]);
?>

<div class="col-sm-3">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblproductsalesearch-union_code'); ?>        
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>