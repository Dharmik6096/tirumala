<?php

use app\components\ActiveForm;
?>

<div class="search-filter large-search <?= isset($hideSearchBtn) && $hideSearchBtn ? 'no_pointer_disabled_with_clr' : '' ?>">

    <?php
    $form = ActiveForm::begin([
        'method' => 'get'
    ]);
    ?>

    <div class="col-sm-2 create_fields">
        <?= Yii::$app->controls->date($searchModel, $form, 'transaction_date', '', TRUE, date('Y-m-d'), TRUE, true); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($searchModel, $form, 'tblmedicinestocktransfertxnsearch-union_code', 'plant_code', $searchModel->getAttributeLabel('plant_code'), false, '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($searchModel, $form, 'tblmedicinestocktransfertxnsearch-plant_code', 'mcc_plant_code', $searchModel->getAttributeLabel('mcc_plant_code'), false, '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($searchModel, $form, 'tblmedicinestocktransfertxnsearch-mcc_plant_code', 'bmc_code', $searchModel->getAttributeLabel('bmc_code'), false, '', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($searchModel, $form, 'tblmedicinestocktransfertxnsearch-bmc_code', 'dcs_code', Yii::t('app', 'DCS'), false, '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->UserList($searchModel, $form, 'tblmedicinestocktransfertxnsearch-union_code,tblmedicinestocktransfertxnsearch-plant_code,tblmedicinestocktransfertxnsearch-mcc_plant_code,tblmedicinestocktransfertxnsearch-bmc_code,tblmedicinestocktransfertxnsearch-dcs_code', 'from_user_code', $searchModel->getAttributeLabel('From User'), FALSE, FALSE, '/veterinary/tbl-medicine-stock/user-list'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->UserList($searchModel, $form, 'tblmedicinestocktransfertxnsearch-union_code', 'to_user_code', $searchModel->getAttributeLabel('to User'), FALSE, FALSE, '/veterinary/tbl-medicine-stock/all-user-list'); ?>
    </div>
    <div class="col-sm-2 create_fields">
        <?= $form->field($searchModel, 'remarks')->textInput() ?>
    </div>
    <div class="col-sm-2 mt20">
        <?= $form->field($searchModel, 'medicine_wise', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    
    <div class="col-sm-1 mt23 <?= isset($hideSearchBtn) && $hideSearchBtn ? 'default_hide_input' : '' ?>">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>