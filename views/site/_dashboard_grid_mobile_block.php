<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php $url = Url::to(['site/get-installed-mpps', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed VLC') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_mpp"><?= $blocks_data[0][0]['total_app_installed_mpp'] ?>/<?= $blocks_data[0][0]['total_mpp'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-installed-members', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed Farmers') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_member"><?= $blocks_data[0][0]['total_app_installed_member'] ?>/<?= $blocks_data[0][0]['total_member'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-installed-employees', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed Employees') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_employee"><?= $blocks_data[0][0]['total_app_installed_employee'] ?>/<?= $blocks_data[0][0]['total_employee'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-installed-supervisors', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed Route Supervisor') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_supervisor"><?= $blocks_data[0][0]['total_app_installed_supervisor'] ?>/<?= $blocks_data[0][0]['total_supervisor'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-installed-managers', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed Area Incharge') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_manager"><?= $blocks_data[0][0]['total_app_installed_az_manager'] ?>/<?= $blocks_data[0][0]['total_az_manager'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-installed-other-staff', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Installed Other Employees') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_other_staff"><?= $blocks_data[0][0]['total_app_installed_other_Staff'] ?>/<?= $blocks_data[0][0]['total_other_Staff'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-mpps', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed VLC') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_mpp"><?= $blocks_data[0][0]['total_app_not_installed_mpp'] ?>/<?= $blocks_data[0][0]['total_mpp'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-members', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed Farmers') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_member"><?= $blocks_data[0][0]['total_app_not_installed_member'] ?>/<?= $blocks_data[0][0]['total_member'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-employees', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed Employees') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_employee"><?= $blocks_data[0][0]['total_app_not_installed_employee'] ?>/<?= $blocks_data[0][0]['total_employee'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-supervisors', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed Route Supervisor') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_supervisor"><?= $blocks_data[0][0]['total_app_not_installed_supervisor'] ?>/<?= $blocks_data[0][0]['total_supervisor'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-managers', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed Area Incharge') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_manager"><?= $blocks_data[0][0]['total_app_not_installed_az_manager'] ?>/<?= $blocks_data[0][0]['total_az_manager'] ?></h4>
    </div>
</a>
<?php $url = Url::to(['site/get-not-installed-other-staff', 'date' => $date, 'union' => $union_code]); ?>
<a href="<?= $url; ?>" class='href_link' >
    <div class="div_grid_dash_mobile_block dashboardWidgetDetailPortion">
        <p class="dash_block_header"><?= Yii::t('app', 'Not Installed Other Employees') ?></p>
        <p class="dash_block_description"><small>On <?= Yii::$app->controls->view_date($date) ?></small></p>
        <h4 class="dash_block_value block_value" id="mobile_block_other_staff"><?= $blocks_data[0][0]['total_app_not_installed_other_Staff'] ?>/<?= $blocks_data[0][0]['total_other_Staff'] ?></h4>
    </div>
</a>
