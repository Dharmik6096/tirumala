<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTempSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-search search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['applicabilty-approve']
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldcspurchaserateapplicabitityaliassearch-union_code', 'plant_code', Yii::t('app', 'Plant'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldcspurchaserateapplicabitityaliassearch-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldcspurchaserateapplicabitityaliassearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-2">
        <?= Html::hiddenInput('rate_for', 'dcs', ['id' => 'rate_for']); ?>
        <?= Yii::$app->dropdown->dcsRateChart($model, $form, 'tbldcspurchaserateapplicabitityaliassearch-union_code,rate_for', 'purchase_rate_code', 'Rate Id'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('approved_status', $model, $form, 'form-group', 'Status', false, 'status', false); ?>
    </div>

    <div class="col-sm-2 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

