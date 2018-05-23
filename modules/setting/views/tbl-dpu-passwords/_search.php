<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\setting\models\TblDpuPasswordsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['create'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcssearch-f_union_code', 'f_plant_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcssearch-f_plant_code', 'f_mcc_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcssearch-f_mcc_code', 'f_bmc_code'); ?>
</div>
<div class="form-group">
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
