<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC Code') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC Name') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= empty($customer_tpye) ? (Yii::t('app', 'DCS Code')) : Yii::t('app', 'Code') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= empty($customer_tpye) ? (Yii::t('app', 'DCS Name')) : Yii::t('app', 'Name') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'QTY') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'FAT') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'SNF') ?></th>
                <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'AMOUNT') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                ?>
                <tr>
                    <td><?= $data['mcc_code'] ?></td>
                    <td><?= $data['mcc_name'] ?></td>
                    <td><?= $data['dcs_code'] ?></td>
                    <td><?= $data['dcs_name'] ?></td>
                    <td><?= $data['qty'] ?></td>
                    <td><?= $data['fat'] ?></td>
                    <td><?= $data['snf'] ?></td>
                    <td><?= $data['amount'] ?></td>
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