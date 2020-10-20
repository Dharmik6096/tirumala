<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Periodic Online Data Comparision'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashboard_filter_hit_count_tab', ['model' => $model, 'id' => 'tbl_hits_counts', 'container' => 'tbl_hits_counts', 'date_range' => true, 'date_range_class' => 'col-sm-8', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'hit_range_1_from' => 'hit_range_1_from', 'hit_range_1_to' => 'hit_range_1_to', 'hit_range_2_from' => 'hit_range_2_from', 'hit_range_2_to' => 'hit_range_2_to', 'date' => $date, 'table_class' => 'tbl_hits_counts', 'popup_title' => Yii::t('app', 'Previous and Current Counts')]); ?>

            <div class="table-responsive dashboard_tbl h450 cont milk-collection">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="dashboardWidgetDetailPortion" rowspan="2"><?= Yii::t('app', 'Union') ?></th>
                            <th class="dashboardWidgetDetailPortion" rowspan="2"><?= Yii::t('app', 'BMC') ?></th>
                            <th class="dashboardWidgetDetailPortion" rowspan="2"><?= Yii::t('app', 'DCS') ?></th>
                            <th class="dashboardWidgetDetailPortion" colspan="2"><?= Yii::t('app', 'Online Shift Count') ?></th>
                        </tr>
                        <tr>
                            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Previous') ?></th>
                            <th class="dashboardWidgetDetailPortion"><?= Yii::t('app', 'Current') ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbl_hits_counts_container" class="">
                        <tr><td colspan="7">No Data Available.</td></tr>
                    </tbody>
                </table>
            </div>
            <!--<div id="tbl_hits_counts_container" class="cont milk-collection"></div>-->
        </div>
    </div>
</div>