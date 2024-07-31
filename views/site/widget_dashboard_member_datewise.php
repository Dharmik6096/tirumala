<div class="col-sm-6">
    <div class="col-sm-12 text-center member-datewise active dashboard_widget_heading dashboardWidgetHeader"><?= Yii::t('app', 'Farmer Wise Collection'); ?></div>
    <div class="flt">
        <div id="member-datewise">
            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'member_datewise', 'url' => $chart_url, 'container' => 'member_datewise_container', 'date_range' => true, 'range2' => false, 'range_id1' => 'farmer_coll', 'range_id2' => 'farmer_coll1', 'shift' => false, 'type' => 'column', 'title' => '', 'table_popup' => true, 'table_class' => 'member_datewise_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Farmer Wise Collection'), 'date_range_class'=>'col-sm-4', 'union' => $union, 'bmc_code' => $bmc_code, 'dcs_code' => $dcs_code, 'member_code' => $member_code, 'qlt_param' => 'qlt_param1']); ?>
            <div id="member_datewise_container" class="cont"></div>
        </div>
    </div>
</div>