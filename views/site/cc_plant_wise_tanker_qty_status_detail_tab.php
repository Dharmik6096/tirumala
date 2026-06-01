<?php
use yii\web\View;
use yii\helpers\Url;
?>
<script>
    if ($('#cc_plant_wise_tanker_qty_status_title').length) {
        $('#cc_plant_wise_tanker_qty_status_title').html('<?= (isset($widget_title) ? $widget_title : Yii::t('app', 'CC & Plant Wise Tanker Qty/Status Detail')) ?>');
    }
</script>
<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'From CC') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'To Plant') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Trips') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Empty Intransit Hours') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Load Intransit Hour') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'CC qty') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Plant Qty') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Dev') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Dev%') ?></th>
        </tr>
        </thead>
        <?php if (!empty($output)) { ?>
             <tbody>
            <?php foreach ($output as $data) { ?>
                <tr>
                    <td><?= isset($data['From_CC']) ? $data['From_CC'] : '' ?></td>
                    <td><?= isset($data['To_Plant']) ? $data['To_Plant'] : '' ?></td>
                    <td><?= isset($data['Trips']) ? $data['Trips'] : '' ?></td>

                    <?php if (isset($data['Empty_InTransit_Hrs'])): ?>
                        <td><?= $data['Empty_InTransit_Hrs'] ?></td>
                        <td><?= $data['Load_InTransit_Hrs'] ?></td>
                        <td><?= $data['CC_Qty'] ?></td>
                        <td><?= $data['Plant_Qty'] ?></td>
                        <td><?= $data['DEV'] ?></td>

                    <?php else: ?>
                        <td><?= isset($data['Avg_Empty_InTransit_Hrs']) ? $data['Avg_Empty_InTransit_Hrs'] : '' ?></td>
                        <td><?= isset($data['Avg_Load_InTransit_Hrs']) ? $data['Avg_Load_InTransit_Hrs'] : '' ?></td>
                        <td><?= isset($data['Avg_CC_Qty']) ? $data['Avg_CC_Qty'] : '' ?></td>
                        <td><?= isset($data['Avg_Plant_Qty']) ? $data['Avg_Plant_Qty'] : '' ?></td>
                        <td><?= isset($data['Avg_DEV']) ? $data['Avg_DEV'] : '' ?></td>
                    <?php endif; ?>
                    <td><?= isset($data['DEV%']) ? $data['DEV%'] : '' ?></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php } else { ?>
            <tbody>
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
            </tbody>
        <?php } ?>

    </table>
</div>
