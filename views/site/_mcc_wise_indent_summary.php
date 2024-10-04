<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC Code') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'MCC Name') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Date') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Indent List') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Indent Feed Stock') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Approved Indent') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Approved Feed Stock') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Indent Dispatch') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Dispatch Feed Stock') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Cancelled Indent') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Cancelled QTY') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Rejected Indent') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Rejected QTY') ?></th>
                <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Farmer Sale') ?></th>
            </tr>
        </thead>
        <?php
        if (!empty($output)) {
            foreach ($output as $data) {
                $indentListStyle = ($data['indent_list'] == 0) ? 'style="background-color: #ff000099; color: #fff;"' : '';
                ?>
                <tr>
                    <td><?= $data['mcc_ref_code'] ?></td>
                    <td><?= $data['mcc_name'] ?></td>
                    <td><?= $data['dates'] ?></td>
                    <td <?= $indentListStyle ?>><?= $data['indent_list'] ?></td>
                    <td><?= $data['feed_stock'] ?></td>
                    <td><?= $data['approved_indent'] ?></td>
                    <td><?= $data['approved_feed_stock'] ?></td>
                    <td><?= $data['indent_dispatch'] ?></td>
                    <td><?= $data['feed_dispatch'] ?></td>
                    <td><?= $data['cancelled_indent'] ?></td>
                    <td><?= $data['cancelled_qty'] ?></td>
                    <td><?= $data['rejected_indent'] ?></td>
                    <td><?= $data['rejected_qty'] ?></td>
                    <td><?= $data['farmer_sale'] ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr><td colspan="14">No Data Available.</td></tr>
        <?php }
        ?>

    </table>
</div>