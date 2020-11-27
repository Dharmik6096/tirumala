<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
            <tr>
                <?php
                if ($widget_for == 'mcc') {
                    ?>
                    <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Union') ?></th>
                    <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC Code') ?></th>
                    <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC') ?></th>
                    <?php
                } else {
                    ?>
                    <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Union Code') ?></th>
                    <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Union') ?></th>
                    <?php
                }
                ?>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Date') ?></th>
                <th class="max_w35 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Shift') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Active DCS') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total DCS') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Online') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Manual') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Pendrive') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Farmer Quantity') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'RMRD Quantity') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'RMRD DCS Count') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Quantity Difference') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                ?>
                <tr>
                    <?php
                    if ($widget_for == 'mcc') {
                        ?>
                        <td><?= $data['union_name'] ?></td>
                        <td><?= $data['mcc_code'] ?></td>
                        <td><?= $data['mcc_name'] ?></td>
                    <?php } else {
                        ?>
                        <td><?= $data['union_code'] ?></td>
                        <td><?= $data['union_name'] ?></td>
                        <?php
                    }
                    ?>
                    <td><?= $data['date'] ?></td>
                    <td><?= $data['shift_name'] ?></td>
                    <td><?= $data['active_mpp'] ?></td>
                    <td><?= $data['total_mpp'] ?></td>
                    <td><?= $data['total_online'] ?></td>
                    <td><?= $data['total_manual'] ?></td>
                    <td><?= $data['total_pendrive'] ?></td>
                    <td><?= $data['total_farmer_qty'] ?></td>
                    <td><?= $data['total_rmrd_qty'] ?></td>
                    <td><?= $data['rmrd_count'] ?></td>
                    <td><?= $data['qty_diff'] ?></td>
                </tr> 
                <?php
            }
        } else {
            ?>
            <tr><td colspan="13">No Data Available.</td></tr>
        <?php }
        ?>
    </table>
</div>