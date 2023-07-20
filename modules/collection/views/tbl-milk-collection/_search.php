<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
$multiple = isset($multiple) ? TRUE : FALSE;
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
//                'action' => ['get-temp-data'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectionsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectionsearch-bmc_code', 'dcs_code', Yii::t('app', 'Society'), $multiple); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmilkcollectionsearch-dcs_code', '', Yii::t('app', 'Member')); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>

    <div class="col-sm-3 mt18">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>