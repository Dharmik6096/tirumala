<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTempSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-search search-filter">

    <?php
    $form = ActiveForm::begin([
                'action' => ['get-temp-data'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmilkcollectiontemp-union_code', '', false); ?>            
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('approval_status', $model, $form, 'form-group', false, false, 'is_approved', false); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('update_status', $model, $form, 'form-group', false, false, 'is_updated', false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
