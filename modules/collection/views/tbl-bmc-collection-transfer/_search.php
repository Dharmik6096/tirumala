<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class=" search-filter large-search">

    <?php
    $form = ActiveForm::begin([
//                'action' => ['get-temp-data'],
                'method' => 'get',
    ]);
    ?>
    <div class="row col-sm-12">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmccollectiontransfersearch-union_code', 'from_plant_code', $model->getAttributeLabel('from_plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmccollectiontransfersearch-from_plant_code', 'from_mcc_plant_code', $model->getAttributeLabel('from_mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->all_routes($model, $form, 'tblbmccollectiontransfersearch-from_plant_code,tblbmccollectiontransfersearch-from_mcc_plant_code,tblbmccollectiontransfersearch-from_mcc_plant_code', 'route_code', $model->getAttributeLabel('route_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
        </div>
        <div class="col-sm-2 shift">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift_code'), false, 'from_shift_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
        </div>
        <div class="col-sm-2 shift">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift_code'), false, 'to_shift_code'); ?>
        </div>

        <div class="col-sm-3 mt23">
            <?= Yii::$app->controls->search(); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>