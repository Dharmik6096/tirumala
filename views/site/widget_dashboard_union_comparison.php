<div class="col-sm-6">
    <div class="col-sm-12 text-center widget-tab society-compare active dashboard_widget_heading dashboardWidgetHeader"><?= Yii::t('app', 'Society Milk Collection'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'union_comparison', 'url' => $chart_url, 'container' => 'union_comparison_container', 'date_range' => true, 'range2' => true, 'shift' => false, 'type' => 'column', 'title' => '', 'table_popup' => true, 'table_class' => 'union_comparison_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Society Milk Collection')]); ?>
            <div id="union_comparison_container" class="cont"></div>
        </div>
    </div>
</div>