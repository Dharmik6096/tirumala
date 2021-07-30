<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Top 5 RMRD Collection'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_top_rmrd_collection', ['model' => $model, 'id' => 'TopRMRDCollection', 'container' => 'TopRMRDCollection', 'date_range' => true, 'date_range_class' => 'col-sm-4', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'range_id1' => 'top_rmrd_tab_dt1', 'range_id2' => 'top_rmrd_tab_dt2', 'table_class' => 'TopRMRDCollection', 'popup_title' => Yii::t('app', 'Top 5 DCS Collection')]); ?>
            <div id="TopRMRDCollection_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>