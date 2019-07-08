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
//                'action' => ['get-temp-data'],
                'method' => 'get',
    ]);
    ?>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsearch-f_union_code', 'f_plant_code'); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsearch-f_plant_code', 'f_mcc_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('sap_collection_type', $model, $form, 'form-group', false, false, 'sap_collection_type', false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'to_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
