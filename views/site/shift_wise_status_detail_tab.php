<?php
use yii\web\View;
use yii\helpers\Url;

?>
<script>
    if ($('#shift_wise_status_title').length) {
        $('#shift_wise_status_title').html('<?= (isset($widget_title) ? $widget_title : Yii::t('app', 'Shift wise Status Detail')) ?>');
    }
</script>
<div class="table-responsive dashboard_tbl h450">
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'From CC') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'To Plant') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', '>4 Shifts') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', '3 Shifts') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', '2 Shifts') ?></th>
            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', '1 Shifts') ?></th>
        </tr>
        </thead>
        <?php if (!empty($output)) { ?>
             <tbody>
            <?php foreach ($output as $data) {
                //print_r($data);die;
                ?>
                <tr>
                    <td><?= $data['FROM CC'] ?? '' ?></td>
                    <td><?= $data['TO PLANT'] ?? '' ?></td>
                    <td><?= $data['>4 shifts'] ?? '' ?></td>
                    <td><?= $data['3 shifts'] ?? '' ?></td>
                    <td><?= $data['2 shifts'] ?? '' ?></td>
                    <td><?= $data['1 shifts'] ?? '' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        <?php } else { ?>
            <tbody>
            <tr>
                <td colspan="6" class="text-center">No Data Available.</td>
            </tr>
            </tbody>
        <?php } ?>

    </table>
</div>
