<?php

use yii\helpers\Url;
?>
<div class="col-sm-12">
    <div class="col-sm-12 text-center dashboard_widget_heading"><?= Yii::t('app', 'Feed Summary Dashboard'); ?></div>
</div>
<div class="clearfix"></div>
<div class="col-sm-12">

    <?php $baseUrl = Url::to(['site/feed-summary-dashboard-details', 'union' => $union, 'date' => $date]); ?>
    <a href="<?= $baseUrl ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
            <div class="div_mobile_dash_block_content">
                <p class="mobile_dash_block_header"><?= Yii::t('app', 'Opening Balance') ?></p>
                <h4 class="dash_block_value block_value" id="opening_balance">0</h4>
            </div>
        </div>
    </a>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Received') ?></p>
            <h4 class="dash_block_value block_value" id="received">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Inventory Transfer') ?></p>
            <h4 class="dash_block_value block_value" id="inventory_transfer">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Sale') ?></p>
            <h4 class="dash_block_value block_value" id="sale">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Sale Return') ?></p>
            <h4 class="dash_block_value block_value" id="sale_return">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Balance Qty') ?></p>
            <h4 class="dash_block_value block_value" id="balance_qty">0</h4>
        </div>
    </div>
</div>

