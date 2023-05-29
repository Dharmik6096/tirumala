<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Collection Summary') ?></div>
    <div class="dashboard_widget_tables col-sm-3 div_dash_block dashboardWidgetDetailPortion height_115">
        <table class="dash_block_border">
            <th class="dash_block_headers width_l">
                <?= Yii::t('app', 'Milk Collection') ?>
            </th>
            <th class="dash_block_headers width_r">
                <?= Yii::t('app', 'LLPD') ?>
            </th>
            <tr>
                <td class="t_design"><?= Yii::t('app', 'Today : ') ?>   </td>
                <td class="t_design"> <span id="today_milk_collection_llpd"></span></td>
            </tr>
            <tr>
                <td class="t_design_2"><?= Yii::t('app', 'This month cumulative : ') ?>
                <td class="t_design_2"> <span id="cumulative_milk_collection_llpd"></span></td>
                </td>
            </tr>
        </table>
    </div>

    <div class="dashboard_widget_tables col-sm-3 div_dash_block dashboardWidgetDetailPortion height_115">
        <table class="dash_block_border">
            <th class="dash_block_headers width_l">
                <?= Yii::t('app', 'Quality') ?>
            </th>
            <th class="dash_block_headers width_r">
                <?= Yii::t('app', 'FAT%') ?>
            </th>
            <tr>
                <td class="t_design"><?= Yii::t('app', 'Today : ') ?></td>
                <td class="t_design"> <span id="today_fat_quality"></span></td>
            </tr>
            <tr>
                <td class="t_design_2"><?= Yii::t('app', 'This month cumulative : ') ?></td>
                <td class="t_design_2"><span id="cumulative_fat_quality"></span></td>
            </tr>
        </table>
    </div>

    <div class="dashboard_widget_tables col-sm-3 div_dash_block dashboardWidgetDetailPortion height_115">
        <table class="dash_block_border">
            <th class="dash_block_headers width_l">
                <?= Yii::t('app', 'Quality') ?>
            </th>
            <th class="dash_block_headers width_r">
                <?= Yii::t('app', 'SNF%') ?>
            </th>
            <tr>
                <td class="t_design"><?= Yii::t('app', 'Today : ') ?> </td>
                <td class="t_design"> <span id="today_snf_quality"></span></td>
            </tr>
            <tr>
                <td class="t_design_2"><?= Yii::t('app', 'This month cumulative : ') ?></td>
                <td class="t_design_2"><span id="cumulative_snf_quality"></span></td>
            </tr>
        </table>
    </div>

    <div class="dashboard_widget_tables col-sm-3 div_dash_block dashboardWidgetDetailPortion height_115">
        <table class="dash_block_border">
            <th class="dash_block_headers width_l">
                <?= Yii::t('app', 'Feed Supply') ?>
            </th>
            <th class="dash_block_headers width_r">
                <?= Yii::t('app', 'Tons') ?>
            </th>
            <tr>
                <td class="t_design"><?= Yii::t('app', 'Today : ') ?></td>
                <td class="t_design"> <span id="today_tons_feed_supply"></span></td>
            </tr>
            <tr>
                <td class="t_design_2"><?= Yii::t('app', 'This month cumulative : ') ?></td>
                <td class="t_design_2"> <span id="cumulative_tons_feed_supply"></span></td>
            </tr>
        </table>
    </div>
</div>