<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Analysis'); ?></div>
        <div class="flt">
            <div id="society-compare" class="pl_0">
                <?= $this->render('_dashboard_widget_milk_analysis_vertical', ['model' => $model, 'id' => 'tbl_milk_analysis_vertical_id', 'container' => 'tbl_milk_analysis_vertical_container', 'date_range' => true, 'date_range_class' => 'col-sm-8', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'table_class' => 'tbl_milk_analysis_vertical', 'range_id_from' => 'from_date_milk_analysis_vertical', 'range_id_to' => 'to_date_milk_analysis_vertical','popup_title' => Yii::t('app', 'Milk Analysis')]); ?>
                <?php //$this->render('', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'bmc_coll_widget_container', 'diff_sp_name' => 'bmc_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'bmc_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Milk Analysis')]); ?>
            </div>
        </div>
    </div>
</div>