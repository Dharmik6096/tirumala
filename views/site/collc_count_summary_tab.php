<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Date') ?></th>
                <th class="max_w35 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Shift') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Active MPP') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total MPP') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Online') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Manual') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Total Pendrive') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Farmer Quantity') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'RMRD Quantity') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'RMRD MPP Count') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Quantity Difference') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                ?>
                <tr>
                    <td><?= $data['mcc_code'] ?></td>
                    <td><?= $data['date'] ?></td>
                    <td><?= $data['shift_name'] ?></td>
                    <td><?= $data['total_mpp'] ?></td>
                    <td><?= $data['total_online'] ?></td>
                    <td><?= $data['total_manual'] ?></td>
                    <td><?= $data['total_pendrive'] ?></td>
                    <td><?= $data['total_farmer_qty'] ?></td>
                    <td><?= $data['total_rmrd_qty'] ?></td>
                    <td><?= $data['rmrd_count'] ?></td>
                    <td><?= $data['qty_diff'] ?></td>
                    <td><?= $data['active_mpp'] ?></td>
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