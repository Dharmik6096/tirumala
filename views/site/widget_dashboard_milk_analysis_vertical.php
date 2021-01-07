<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Analysis'); ?></div>
        <div class="flt">
            <div id="society-compare" class="pl_0">
                <?= $this->render('_dashboard_widget_milk_analysis_vertical', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'bmc_coll_widget_container', 'diff_sp_name' => 'bmc_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'bmc_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Milk Analysis')]); ?>
            </div>
        </div>
    </div>
</div>