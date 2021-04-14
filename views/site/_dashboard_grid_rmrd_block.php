<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$class_cols = $class_cols;
$imageIconPath = $this->theme->getUrl('/assets/images/dashboard/');
?>
<?php $url = ''; //Url::to(['site/get-unions', 'date' => $date, 'union_code' => $model->union_code]);               ?>
<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
    <div class="div_dash_block_content">
        <p class="dash_block_header"><?= Yii::t('app', 'Union') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="farmer_rmrd_block_union"><?= $blocks_data[1][0]['pourerUnion'] ?>/<?= $blocks_data[1][0]['totalUnion'] ?></h4>
    </div>
    <div class="div_dash_block_icon"> <img
            src="<?= $imageIconPath . 'company.png' ?>"> </div>
</div>

<?php $url = Url::to(['site/get-rmrd-mccs', 'date' => $date, 'union_code' => $union, 'widget_for' => 'rmrd']); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'MCC') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_mcc"><?= $blocks_data[1][0]['pourerMcc'] ?>/<?= $blocks_data[1][0]['totalMcc'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'mcc.png' ?>"> </div>
    </div>
</a>
<?php $url = Url::to(['site/get-rmrd-dcs', 'date' => $date, 'union_code' => $union, 'widget_for' => 'rmrd']); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'DCS') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_dcs"><?= $blocks_data[1][0]['pourerDcs'] ?>/<?= $blocks_data[1][0]['totalDcs'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>
</a>
<?php $url = Url::to(['site/get-rmrd-vendor', 'date' => $date, 'union_code' => $union, 'widget_for' => 'rmrd', 'type' => 'BULKVEN']); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?> ">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'Bulk Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_blk_vendor"><?= $blocks_data[1][0]['pourerBulkVen'] ?>/<?= $blocks_data[1][0]['totalBulkVen'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>
</a>

<?php $url = Url::to(['site/get-rmrd-vendor', 'date' => $date, 'union_code' => $union, 'type' => 'VLCCVEN']); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>">
        <div class="div_dash_block_content">
            <p class="dash_block_header"><?= Yii::t('app', 'VLCC Vendor') ?></p>
            <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
            <h4 class="dash_block_value block_value" id="farmer_rmrd_block_vlcc_vendor"><?= $blocks_data[1][0]['pourerVlccVen'] ?>/<?= $blocks_data[1][0]['totalVlccVen'] ?></h4>
        </div>
        <div class="div_dash_block_icon"> <img
                src="<?= $imageIconPath . 'dcs.png' ?>"> </div>
    </div>
</a>
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


