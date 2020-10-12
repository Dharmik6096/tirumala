<?php
$imageIconPath = $this->theme->getUrl('/assets/images/dashboard/');
?>

<div class="col-sm-12 farmer_rmrd_block">

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Union') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_union">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'company.png' ?>"> </div>
    </div>


    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'MCC') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
    </div>


    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_dcs">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>


    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display_rmrd ?> ">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Bulk Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_blk_vendor">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>


    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display_rmrd ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'VLCC Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_vlcc_vendor">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Farmer') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_farmer">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'farmer.png' ?>"> </div>
    </div>

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Quantity') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_quantity">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'scale.png' ?>"> </div>
    </div>

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'FATKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_fatkg">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'SNFKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_snfkg">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Amount') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_amount">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'rupee.png' ?>"> </div>
    </div>

</div>