<?php

use yii\widgets\ActiveForm;
use yii\web\View;
$request = Yii::$app->request->queryParams;
$min_date = empty($request["min_date"]) ? '' : $request["min_date"];
$max_date = empty($request["max_date"]) ? '' : $request["max_date"];
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmilkcollectionsearch-union_code'); ?>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <?= Yii::$app->controls->min_max_date('min_date', 'max_date',$min_date,$max_date); ?>
    </div>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
</div>   
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
