<?php
$imageIconPath = $this->theme->getUrl('/assets/images/dashboard/');
?>

<div class="col-sm-12 dashboard_farmer_status">

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Active <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_fatkg">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'active_dcs.png' ?>"> </div>
    </div>
    <!--    <div class="col-sm-6">
    
            <div class="collection background_light">
                <div class="col-sm-6">
                    <p class="block_title">Active <?= Yii::t('app', 'DCS') ?></p>
                    <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                </div>
                <div class="col-sm-6">            
                    <h4 class="block_value" id="dashboard_farmer_status_active_dcs">0</h4>
                </div>
            </div>
        </div>-->

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Installed <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_installed_dcs">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'installed_dcs.png' ?>"> </div>
    </div>
    <!--    <div class="col-sm-6">
            <div class="collection background_dark">                                
                <div class="col-sm-6">
                    <p class="block_title">Installed <?= Yii::t('app', 'DCS') ?></p>
                    <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                </div>
                <div class="col-sm-6">
                    <h4 class="block_value" id="dashboard_farmer_status_installed_dcs">0</h4>
                </div>
            </div>
        </div>-->
    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Online <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value pull-left" id="dashboard_farmer_status_online_dcs">0</h4>
            <p class="dash_block_value mt18 block_other_value">&nbsp;(M:<span id="dashboard_farmer_status_online_dcs_m">0</span> | E:<span id="dashboard_farmer_status_online_dcs_e">0</span>)</p>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'online_dcs.png' ?>"> </div>
    </div>
    <!--    <div class="col-sm-6">
            <div class="collection background_dark">
                <div class="col-sm-6">
                    <p class="block_title">Online <?= Yii::t('app', 'DCS') ?></p>
                    <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                </div>
                <div class="col-sm-6">
                    <h4 class="block_value" id="dashboard_farmer_status_online_dcs">0</h4>
                    <p class="block_other_value">M:<span id="dashboard_farmer_status_online_dcs_m">0</span>|E:<span id="dashboard_farmer_status_online_dcs_e">0</span></p>
                </div>
            </div>
        </div>-->

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Offline <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_offline_dcs">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'offline_dcs.png' ?>"> </div>
    </div>
    <!--    <div class="col-sm-6">
            <div class="collection background_light">
                <div class="col-sm-6">
                    <p class="block_title">Offline <?= Yii::t('app', 'DCS') ?></p>
                    <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                </div>
                <div class="col-sm-6">
                    <h4 class="block_value" id="dashboard_farmer_status_offline_dcs">0</h4>
                </div>
            </div>
        </div>-->
</div>