<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Collection Count Summary'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
        <?= $this->render('_dashboard_filter_collc_count_summary', ['model' => $model, 'id' => 'tbl_collc_count_summary', 'container' => 'tbl_collc_count_summary', 'date_range' => true, 'date_range_class' => 'col-sm-8', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'hit_range_1_from' => 'hit_range_1_from', 'hit_range_1_to' => 'hit_range_1_to', 'hit_range_2_from' => 'hit_range_2_from', 'hit_range_2_to' => 'hit_range_2_to']); ?>
            <div id="tbl_collc_count_summary_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>