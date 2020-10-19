<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Previous and Current Counts'); ?></div>
    <div class="flt">
        <div id="society-compare">
        <?= $this->render('_dashboard_filter_hit_count_tab', ['model' => $model, 'id' => 'tbl_hits_counts', 'container' => 'tbl_hits_counts', 'date_range' => true, 'date_range_class' => 'col-sm-8', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'hit_range_1_from' => 'hit_range_1_from', 'hit_range_1_to' => 'hit_range_1_to', 'hit_range_2_from' => 'hit_range_2_from', 'hit_range_2_to' => 'hit_range_2_to', 'date' => $date,'table_class' => 'tbl_hits_counts','popup_title' => Yii::t('app', 'Previous and Current Counts')]); ?>
            <div id="tbl_hits_counts_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>