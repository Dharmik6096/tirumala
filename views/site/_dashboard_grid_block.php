<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$class_cols = $class_cols;
$imageIconPath = $this->theme->getUrl('/assets/images/dashboard/');
?>
<?php $url = '';//Url::to(['site/get-unions', 'date' => $date, 'union_code' => $model->union_code]);?>
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'Union') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_union"><?= $blocks_data[1][0]['pourerUnion'] ?>/<?= $blocks_data[1][0]['totalUnion'] ?></h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'company.png' ?>"> </div>
    </div>

    <?php $url ='';//Url::to(['site/get-mccs', 'date' => $date, 'union_code' => $model->union_code]);?>
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'MCC') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc"><?= $blocks_data[1][0]['pourerMcc'] ?>/<?= $blocks_data[1][0]['totalMcc'] ?></h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
    </div>

    <?php $url ='';// Url::to(['site/get-dcs', 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code]);?>
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_dcs"><?= $blocks_data[1][0]['pourerDcs'] ?>/<?= $blocks_data[1][0]['totalDcs'] ?></h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>

    <?php $url = ''//Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code]);?>
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'Farmer') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_farmer"><?= $blocks_data[1][0]['pourerMember'] ?>/<?= $blocks_data[1][0]['totalMember'] ?></h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'farmer.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Quantity') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_quantity"><?= $blocks_data[1][0]['totalQty'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'scale.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'FATKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_fatkg"><?= $blocks_data[1][0]['fatKg'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'SNFKG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_snfkg"><?= $blocks_data[1][0]['snfKg'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'totalcount.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Amount') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_amount"><?= $blocks_data[1][0]['amount'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'rupee.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Active <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_active_dcs"><?= $blocks_data[0][0]['activeDcs'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'active_dcs.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Installed <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>As On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_installed_dcs"><?= $blocks_data[0][0]['installedDcs'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'installed_dcs.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Online <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value pull-left" id="dashboard_farmer_status_online_dcs"><?= $blocks_data[0][0]['onlineDcs'] ?></h4>
            <p class="dash_block_value mt18 block_other_value">&nbsp;(M:<span id="dashboard_farmer_status_online_dcs_m"><?= $blocks_data[0][0]['onlineDcsM'] ?></span> | E:<span id="dashboard_farmer_status_online_dcs_e"><?= $blocks_data[0][0]['onlineDcsE'] ?></span>)</p>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'online_dcs.png' ?>"> </div>
    </div>

    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Offline <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_offline_dcs"><?= $blocks_data[0][0]['offlineDcs'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'offline_dcs.png' ?>"> </div>
    </div>