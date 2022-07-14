<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->from_date = !empty($model->from_date) ? $model->from_date : date('d-m-Y');
$model->to_date = !empty($model->to_date) ? $model->to_date : date('d-m-Y');
?>

<div class="search-filter large-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code', Yii::t('app', 'UNION')); ?>
    </div>
    <!--    <div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccshiftlocksearch-f_union_code', 'f_plant_code', Yii::t('app', 'PLANT')); ?>
       </div>
       <div class="col-sm-2 ">
    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmccshiftlocksearch-f_plant_code', 'f_mcc_code', Yii::t('app', 'MCC'), TRUE); ?>
       </div>  -->
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('channel', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('x_col1'), false, 'x_col1'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->channel_bmc($model, $form, 'tblmccshiftlocksearch-x_col1', 'f_bmc_code', Yii::t('app', 'BMC'), TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-1 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-1 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>
    <div class="col-sm-2 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>