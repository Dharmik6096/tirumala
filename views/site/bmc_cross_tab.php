<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="table-responsive h450">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'BMC Code') ?></th>
                <th><?= Yii::t('app', 'BMC Name') ?></th>
                <th><?= Yii::t('app', 'Date') ?></th>
                <th><?= Yii::t('app', 'Shift') ?></th>
                <th><?= Yii::t('app', 'DCS Count') ?></th>
                <th><?= Yii::t('app', 'Completed') ?></th>
                <th><?= Yii::t('app', 'Pending') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                $date = $data['dtdate'] . ' ';
                $date.= $data['Shift'] == 'E' ? '18:00:00' : '06:00:00';
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'cross-tab-modal', 'data-p_date' => $date, 'data-bmc_name' => $data['bmc_name'], 'data-shift' => $data['Shift'], 'data-union_Code' => $union_code, 'data-p_bmc_code' => $data['bmc_code']];
                ?>
                <tr>
                    <td><?= $data['bmc_code'] ?></td>
                    <td><?= $data['bmc_name'] ?></td>
                    <td><?= Yii::$app->controls->view_date($data['dtdate']) ?></td>
                    <td><?= $data['Shift'] ?></td>
                    <td><?= $data['DCS_Count'] ?></td>
                    <?php $options['data-p_type'] = 'Completed'; ?>
                    <td><?= GhostHtml::a_alert($data['Completed'], ['/site/bmc-cross-tab-details', 'p_date' => $data['dtdate'], 'shift' => $data['Shift'], 'union_Code' => $union_code, 'p_bmc_code' => $data['bmc_code'], 'p_type' => 'Completed'], $options); ?></td>
                    <?php $options['data-p_type'] = 'Pending'; ?>
                    <td><?= GhostHtml::a_alert($data['Pending'], ['/site/bmc-cross-tab-details', 'p_date' => $data['dtdate'], 'shift' => $data['Shift'], 'union_Code' => $union_code, 'p_bmc_code' => $data['bmc_code'], 'p_type' => 'Pending'], $options); ?></td>
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