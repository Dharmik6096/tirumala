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
        <tr>
            <td>02003</td>
            <td>15-10-2020</td>
            <td>M</td>
            <td>78</td>
            <td>37</td>
            <td>27</td>
            <td>0</td>
            <td>10</td>
            <td>1306.33</td>
            <td>1875.90</td>
            <td>51</td>
            <td>-569.57</td>
        </tr>
        <?php
        // if (!empty($output)) {
        //     foreach ($output as $data) {
        //         $date = $data['dtdate'] . ' ';
        //         $date .= $data['Shift'] == 'E' ? '18:00:00' : '06:00:00';
        //         $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'cross-tab-modal', 'data-p_date' => $date, 'data-bmc_name' => $data['bmc_name'], 'data-shift' => $data['Shift'], 'data-union_Code' => $union_code, 'data-p_bmc_code' => $data['bmc_code']];
                ?>
                <!-- <tr>
                    <td><?php //$data['bmc_code'] ?></td>
                    <td><?php //Yii::$app->controls->view_date($data['dtdate']) ?></td>
                    <td><?php //$data['Shift'] ?></td>
                    <td><?php //$data['DCS_Count'] ?></td>
                    <?php //$options['data-p_type'] = 'Completed'; ?>
                    <td><?php //GhostHtml::a_alert($data['Completed'], ['/site/bmc-cross-tab-details', 'p_date' => $data['dtdate'], 'shift' => $data['Shift'], 'union_Code' => $union_code, 'p_bmc_code' => $data['bmc_code'], 'p_type' => 'Completed'], $options); ?></td>
                    <?php //$options['data-p_type'] = 'Pending'; ?>
                    <td><?php //GhostHtml::a_alert($data['Pending'], ['/site/bmc-cross-tab-details', 'p_date' => $data['dtdate'], 'shift' => $data['Shift'], 'union_Code' => $union_code, 'p_bmc_code' => $data['bmc_code'], 'p_type' => 'Pending'], $options); ?></td>
                    <?php //$options['data-p_type'] = 'No_Collection'; ?>
                    <td><?php //GhostHtml::a_alert($data['No_Collection'], ['/site/bmc-cross-tab-details', 'p_date' => $data['dtdate'], 'shift' => $data['Shift'], 'union_Code' => $union_code, 'p_bmc_code' => $data['bmc_code'], 'p_type' => 'Pending'], $options); ?></td>
                </tr> -->
                <?php
        //     }
        // } else {
            ?>
            <!-- <tr><td colspan="7">No Data Available.</td></tr> -->
        <?php //}
        ?>
    </table>
</div>