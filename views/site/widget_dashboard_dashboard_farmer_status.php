<?php

use yii\helpers\Url;

$eiplCode = strtolower(\Yii::$app->session['eiplCode']);
$imageIconPathClient = $this->theme->getUrl('/assets/' . $eiplCode . '/images/dashboard/');
$imageIconPathEipl = $this->theme->getUrl('/assets/images/dashboard/');
$imageIconPath = is_dir(\Yii::getAlias('@webroot') . ('/themes/emilk/assets/' . $eiplCode . '/images/dashboard/')) ? $imageIconPathClient : $imageIconPathEipl;

?>

<div class="col-sm-12 dashboard_farmer_status">

    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Active <?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_active_dcs<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
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
    <?php $url = Url::to(['site/get-society-status', 'date' => $date, 'union_code' => $union, 'shift'=>$model->shift , 'status' => 'Installed']); ?>
    <a href="<?= $url; ?>" class='href_link' >  
        <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header">Installed <?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>PAs On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="dashboard_farmer_status_installed_dcs<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'installed_dcs.png' ?>"> </div>
        </div>
    </a>
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
    <?php $url = Url::to(['site/get-society-status', 'date' => $date, 'union_code' => $union, 'shift'=>$model->shift, 'status' => 'Online']); ?>
    <a href="<?= $url; ?>" class='href_link' >
        <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header">Online <?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

                <h4 class="dash_block_value block_value pull-left" id="dashboard_farmer_status_online_dcs<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
                <p class="dash_block_value mt18 block_other_value">&nbsp;(M:<span id="dashboard_farmer_status_online_dcs_m<?= !empty($append_id) ? $append_id : ''; ?>">0</span> | E:<span id="dashboard_farmer_status_online_dcs_e<?= !empty($append_id) ? $append_id : ''; ?>">0</span>)</p>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'online_dcs.png' ?>"> </div>
        </div>
    </a>
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

    <?php $url = Url::to(['site/get-society-status', 'date' => $date, 'union_code' => $union, 'shift'=>$model->shift, 'status' => 'Offline']); ?>
    <a href="<?= $url; ?>" class='href_link' >
        <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header">Offline <?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

                <h4 class="dash_block_value block_value" id="dashboard_farmer_status_offline_dcs<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
            </div>
            <div class="div_dash_block_icon"> <img
                    src="<?= $imageIconPath . 'offline_dcs.png' ?>"> </div>
        </div>
    </a>
    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Collection Not Done') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_collection_not_done<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'collection.png' ?>"> </div>
    </div>
    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header">Non Functional <?= Yii::t('app', 'DCS') ?> Count</p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_non_functuional_dcs_count<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'functional.png' ?>"> </div>
    </div>
    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Complaint Registered ') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_complaint_registered<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'compaint.png' ?>"> </div>
    </div>
    <div class="col-sm-3 div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Non Complaint Registered') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>

            <h4 class="dash_block_value block_value" id="dashboard_farmer_status_non_complaint_registered<?= !empty($append_id) ? $append_id : ''; ?>">0</h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'non_complaint.png' ?>"> </div>
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