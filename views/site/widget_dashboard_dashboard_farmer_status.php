<div class="col-sm-12 dashboard_farmer_status <?= $display_rmrd?>">
    <div class="col-sm-6">
        <div class="collection background_light">
            <div class="col-sm-6">
                <p class="block_title">Active <?= Yii::t('app', 'DCS') ?></p>
                <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            </div>
            <div class="col-sm-6">            
                <h4 class="block_value" id="dashboard_farmer_status_active_dcs">0</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="collection background_dark">                                
            <div class="col-sm-6">
                <p class="block_title">Installed <?= Yii::t('app', 'DCS') ?></p>
                <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            </div>
            <div class="col-sm-6">
                <h4 class="block_value" id="dashboard_farmer_status_installed_dcs">0</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
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
    </div>
    <div class="col-sm-6">
        <div class="collection background_light">
            <div class="col-sm-6">
                <p class="block_title">Offline <?= Yii::t('app', 'DCS') ?></p>
                <p class="block_other_info"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            </div>
            <div class="col-sm-6">
                <h4 class="block_value" id="dashboard_farmer_status_offline_dcs">0</h4>
            </div>
        </div>
    </div>
</div>