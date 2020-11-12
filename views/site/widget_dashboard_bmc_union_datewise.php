<div class="col-sm-6 <?= $display_rmrd?>">
    <div class="col-sm-12 text-center bmc-datewise active dashboard_widget_heading dashboardWidgetHeader"><?= Yii::t('app', 'BMC Milk Collection - Date Wise'); ?></div>
    <div class="flt">
        <div id="bmc-datewise">
            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'bmc_union_datewise', 'url' => $chart_url, 'container' => 'bmc_union_datewise_container', 'date_range' => true, 'range2' => false, 'range_id1' => 'comp1', 'range_id2' => 'comp2', 'shift' => false, 'type' => 'column', 'title' => '', 'range_id1' => 'bmc_date_wise_from_Date', 'range_id2' => 'bmc_date_wise_to_Date', 'table_popup' => true, 'table_class' => 'bmc_union_datewise_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Milk Collection - Date Wise'), 'date_range_class'=>'col-sm-4']); ?>
            <div id="bmc_union_datewise_container" class="cont"></div>
        </div>
    </div>
</div>