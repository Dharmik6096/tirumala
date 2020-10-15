<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Union') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'BMC') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'DCS') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Previous Count') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Current Count') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                ?>
                <tr>
                    <td><?= $data['union_name'] ?></td>
                    <td><?= $data['bmc_name'] ?></td>
                    <td><?= $data['dcs_name'] ?></td>
                    <td><?= $data['prev_count'] ?></td>
                    <td><?= $data['current_count'] ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr><td colspan="7">No Data Available.</td></tr>
        <?php }
        ?>
    </table>
</div>