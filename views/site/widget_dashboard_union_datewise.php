<div class="col-sm-6">
    <div class="col-sm-12 text-center society-datewise active dashboard_widget_heading"><?= Yii::t('app', 'Society Milk Collection - Date Wise'); ?></div>
    <div class="flt">
        <div id="society-datewise">
            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_datewise', 'url' => $chart_url, 'container' => 'union_datewise_container', 'date_range' => true, 'range2' => false, 'range_id1' => 'comp1', 'range_id2' => 'comp2', 'shift' => false, 'type' => 'column', 'title' => '', 'table_popup' => true, 'table_class' => 'union_datewise_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Society Milk Collection - Date Wise'), 'date_range_class'=>'col-sm-4']); ?>
            <div id="union_datewise_container" class="cont"></div>
        </div>
    </div>
</div>