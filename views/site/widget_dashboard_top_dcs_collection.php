<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Top 5 DCS Collection'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_top_collection', ['model' => $model, 'id' => 'TopDCSCollection', 'container' => 'TopDCSCollection', 'date_range' => true, 'date_range_class' => 'col-sm-4', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'range_id1' => 'top_tab_dt1', 'range_id2' => 'top_tab_dt2', 'table_class' => 'TopDCSCollection', 'popup_title' => Yii::t('app', 'Top 5 DCS Collection')]); ?>
            <div id="TopDCSCollection_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>