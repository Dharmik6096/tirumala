<?php

use yii\helpers\Url;

$eiplCode = strtolower(\Yii::$app->session['eiplCode']);
$imageIconPathClient = $this->theme->getUrl('/assets/' . $eiplCode . '/images/dashboard/');
$imageIconPathEipl = $this->theme->getUrl('/assets/images/dashboard/');
$imageIconPath = is_dir(\Yii::getAlias('@webroot') . ('/themes/emilk/assets/' . $eiplCode . '/images/dashboard/')) ? $imageIconPathClient : $imageIconPathEipl;

if (empty($display_rmrd)) {
    $union = 'site/get-rmrd-unions';
    $mcc = 'site/get-rmrd-mccs';
    $bmc = 'site/get-rmrd-bmcs';
    $dcs = 'site/get-rmrd-dcs';
} else {
    $union = 'site/get-unions';
    $mcc = 'site/get-mccs';
    $bmc = 'site/get-bmcs';
    $dcs = 'site/get-dcs';
}
?>

<div class="col-sm-12 farmer_rmrd_blockqq">

    <?php $url = Url::to([$union, 'date' => $date, 'union_code' => $model->union_code, 'shift' => $model->shift]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
                <div class="div_dash_block_content">
                    <p class="dash_block_header"><?= Yii::t('app', 'Union') ?></p>
                    <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                    <h4 class="dash_block_value block_value" id="farmer_rmrd_block_union<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
                </div>
                <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'company.png' ?>"></div>
            </div>
        </div>
    </a>

    <?php $url = Url::to([$mcc, 'date' => $date, 'union_code' => $model->union_code, 'shift' => $model->shift]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'MCC') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
        </div>
    </a>
    <?php $url = Url::to([$bmc, 'date' => $date, 'union_code' => $model->union_code, 'shift' => $model->shift]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'BMC') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_bmc<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
        </div>
    </a>
    <?php $url = Url::to([$dcs, 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code, 'shift' => $model->shift]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'DCS') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_dcs<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
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
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_blk_vendor<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'dcs.png' ?>"></div>
        </div>
    <?php } ?>


    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display_rmrd ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'VLCC Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_vlcc_vendor<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'dcs.png' ?>"></div>
    </div>

    <?php $url = Url::to(['site/get-farmers', 'date' => $date, 'union_code' => $model->union_code, 'mcc_code' => $model->mcc_code, 'shift' => $model->shift]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols . ' ' . $display ?>">
            <div class="div_dash_block_content">
                <p class="dash_block_header"><?= Yii::t('app', 'Farmer') ?></p>
                <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
                <h4 class="dash_block_value block_value" id="farmer_rmrd_block_farmer<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
            </div>
            <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'farmer.png' ?>"></div>
        </div>
    </a>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Qty | LD Qty') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?> | On <?= Yii::$app->controls->view_date(date('Y-m-d', strtotime('-1 day', strtotime($date)))) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_quantity<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'scale.png' ?>"></div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'FAT KG | AVG FAT') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_fatkg<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'totalcount.png' ?>"></div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'SNF KG | AVG SNF') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_snfkg<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'totalcount.png' ?>"></div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Amount | Eff. Rate | Rate') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value font-size-14" id="farmer_rmrd_block_amount<?= !empty($append_id) ? $append_id : ''; ?>">0 | 0 | 0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'rupee.png' ?>"></div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'TS KG') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_ts_kg_tab<?= !empty($append_id) ? $append_id : ''; ?>">0/0</h4>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'totalcount.png' ?>"></div>
    </div>

    <div class="div_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'App Ack Request') ?></p>
            <table class="width_100 dashboard_widget_table">
                <tr>
                    <td><?= Yii::t('app', 'APP : ') ?><span id="totle_app<?= !empty($append_id) ? $append_id : ''; ?>"></span></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'WS : ') ?><span id="totle_ws<?= !empty($append_id) ? $append_id : ''; ?>"></span></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'MA : ') ?><span id="totle_ma<?= !empty($append_id) ? $append_id : ''; ?>"></span></td>
                </tr>
            </table>
        </div>
        <div class="div_dash_block_icon"> <img src="<?= $imageIconPath . 'totalcount.png' ?>"></div>
    </div>

</div>