<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'BMC Wise Data Receipt Status'); ?></div>
    <div class="flt">
        <div id="society-compare">
            <?= $this->render('_dashborad_filter_cross_tab', ['model' => $model, 'id' => 'BmcWiseCrossTab', 'container' => 'BmcWiseCrossTab', 'date_range' => true, 'date_range_class' => 'col-sm-4', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'range_id1' => 'cross_tab_dt1', 'range_id2' => 'cross_tab_dt2']); ?>
            <div id="BmcWiseCrossTab_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>