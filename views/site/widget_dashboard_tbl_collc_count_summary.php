<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Collection Count Summary'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashboard_filter_collc_count_summary', ['model' => $model, 'id' => 'tbl_collc_count_summary', 'container' => 'tbl_collc_count_summary', 'date_range' => true, 'date_range_class' => 'col-sm-4', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'colc_range_1_from' => 'colc_range_1_from', 'colc_range_1_to' => 'colc_range_1_to', 'table_class' => 'tbl_collc_count_summary', 'popup_title' => Yii::t('app', 'Collection Count Summary'), 'date_picker_class' => 'col-sm-3', 'shift_class' => 'col-sm-2', 'from_date_id' => 'collec_count_summary_from_date', 'to_date_id' => 'collec_count_summary_to_date', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true]); ?>
            <div id="tbl_collc_count_summary_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>