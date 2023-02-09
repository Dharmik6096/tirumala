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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblloanproductsalelockingsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblloanproductsalelockingsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblloanproductsalelockingsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'locking_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>