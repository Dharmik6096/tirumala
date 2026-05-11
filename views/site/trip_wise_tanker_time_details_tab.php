<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;
?>
<script>
    if ($('#trip_wise_tanker_time_title').length) {
        $('#trip_wise_tanker_time_title').html('<?= (isset($widget_title) ? $widget_title : Yii::t('app', 'Trip Wise Tanker Time Details')) ?>');
    }
</script>
<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Vehicle No') ?></th>
            <th class="w100 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Trips') ?></th>
            <th class="max_w35 dashboardWidgetDetailPortion"><?= Yii::t('app', 'Intransit Hours') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Waiting Hours') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Idel Hours') ?></th>
        </tr>
        </thead>
        <?php if (!empty($output)) { ?>
             <tbody>
            <?php foreach ($output as $data) { ?>
                <tr>
                    <td><?= isset($data['Vehicle_No']) ? $data['Vehicle_No'] : '' ?></td>
                    <td><?= isset($data['Trips']) ? $data['Trips'] : '' ?></td>
                    <td><?= isset($data['InTransit_Hrs']) ? $data['InTransit_Hrs'] : (isset($data['Avg_InTransit_Hrs']) ? $data['Avg_InTransit_Hrs'] : (isset($data['Intransit_Hours']) ? $data['Intransit_Hours'] : '')) ?></td>
                    <td><?= isset($data['Waiting_Hours']) ? $data['Waiting_Hours'] : (isset($data['Avg_Waiting_Hours']) ? $data['Avg_Waiting_Hours'] : '') ?></td>
                    <td><?= isset($data['Idle_Hours']) ? $data['Idle_Hours'] : (isset($data['Avg_Idle_Hours']) ? $data['Avg_Idle_Hours'] : (isset($data['Idel_Hours']) ? $data['Idel_Hours'] : '')) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        <?php } else { ?>
            <tbody>
            <tr>
                <td colspan="5" class="text-center">No Data Available.</td>
            </tr>
            </tbody>
        <?php } ?>

    </table>
</div>