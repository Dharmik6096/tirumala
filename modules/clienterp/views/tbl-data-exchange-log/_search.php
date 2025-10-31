<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use app\components\ActiveForm;

?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('process_names', $model, $form, 'form-group padding-right-5', $model->getAttributeLabel('process_name'), false, 'process_name') ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code', Yii::t('app', 'Union')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldataexchangelogsearch-f_union_code', 'f_plant_code', Yii::t('app', 'Plant'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldataexchangelogsearch-f_plant_code', 'f_mcc_plant_code', Yii::t('app', 'MCC'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldataexchangelogsearch-f_mcc_plant_code', 'f_bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldataexchangelogsearch-f_bmc_code', 'f_dcs_code', Yii::t('app', 'Society')); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('send_status', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Status'), false, 'data_post_status') ?> 
    </div>

    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>