<?php

use yii\helpers\Url;

$imageIconPath = $this->theme->getUrl('/assets/images/dashboard/');
if (empty($display_rmrd)) {
    $union = 'site/get-rmrd-unions';
    $mcc = 'site/get-rmrd-mccs';
    $dcs = 'site/get-rmrd-dcs';
} else {
    $union = 'site/get-unions';
    $mcc = 'site/get-mccs';
    $dcs = 'site/get-dcs';
}
?>

<div class="col-sm-12 farmer_rmrd_block">

    <?php $url = Url::to([$union, 'date' => $date, 'union_code' => $model->union_code]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
                <div class="div_dash_block_content">
                    <p class="dash_block_header"><?= Yii::t('app', 'Union') ?></p>
                    <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                    <h4 class="dash_block_value block_value" id="farmer_rmrd_block_union">0/0</h4>
                </div>
                <div class="div_dash_block_icon"> <img
                        src="<?= $imageIconPath . 'company.png' ?>"> </div>
            </div>
        </div>
    </a>

    <?php $url = Url::to([$mcc, 'date' => $date, 'union_code' => $model->union_code]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'MCC') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
        </div>
    </a>

    <?php $url = Url::to([$dcs, 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_dcs">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
        </div>
    </a>

    <?php
    $client_code = \Yii::$app->session->get('eiplCode');
    $client_code = !empty($client_code) ? $client_code : '';
    $client_code = strtolower($client_code);
    if ($client_code != 'gyan') {
        ?>
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display_rmrd ?> ">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'Bulk Vendor') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_blk_vendor">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'dcs.png' ?>"> 
            </div>
        </div>
    <?php } ?>


    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display_rmrd ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'VLCC Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_vlcc_vendor">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>

    <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'Farmer') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_farmer">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'farmer.png' ?>"> </div>
        </div>
    </a>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Quantity') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_quantity">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'scale.png' ?>"> </div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'FATKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_fatkg">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'SNFKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_snfkg">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Amount') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_amount">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'rupee.png' ?>"> </div>
    </div>

</div>