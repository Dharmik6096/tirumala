<?php

use yii\helpers\Url;
?>

<div class="col-sm-12 farmer_rmrd_block">

    <?php $url = Url::to(['site/get-installed-mpps', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Active VLC') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_mpp">0/0</h4>
                </div>
            </div>
        </div>
    </a>
    <?php $url = Url::to(['site/get-installed-members', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total Farmers') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_member">0/0</h4>
                </div>
            </div>
        </div>
    </a>
    <?php $url = Url::to(['site/get-installed-employees', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total Employees') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_employee">0/0</h4>
                </div>
            </div>
        </div>
    </a>
    <?php $url = Url::to(['site/get-installed-supervisors', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total Route Supervisor') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_supervisor">0/0</h4>
                </div>
            </div>
        </div>
    </a>
    <?php $url = Url::to(['site/get-installed-managers', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total Area Incharge') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_manager">0/0</h4>
                </div>
            </div>
        </div>
    </a>
    <?php $url = Url::to(['site/get-installed-other-staff', 'date' => $date, 'union' => $union]); ?>
    <a href="<?= $url ?>" target="_blank">
        <div class="link_hover_effect">
            <div class="div_dash_block dashboardWidgetDetailPortion">
                <div class="div_mobile_dash_block_content">
                    <p class="mobile_dash_block_header"><?= Yii::t('app', 'Other Employees') ?></p>
                    <h4 class="dash_block_value block_value" id="mobile_block_other_staff">0/0</h4>
                </div>
            </div>
        </div>
    </a>
</div>