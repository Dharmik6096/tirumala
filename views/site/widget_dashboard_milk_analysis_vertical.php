<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Analysis'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashboard_widget_milk_analysis_vertical', ['model' => $model, 'id' => 'tbl_milk_analysis_vertical_id', 'container' => 'tbl_milk_analysis_vertical_container', 'table_class' => 'tbl_milk_analysis_vertical', 'popup_title' => Yii::t('app', 'Milk Analysis'), 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'from_date_id' => 'milk_analysis_vertical_from_date', 'to_date_id' => 'milk_analysis_vertical_to_date', 'date_picker_class' => 'col-sm-3', 'shift_class' => 'col-sm-3']); ?>

            <!--<? $this->render('_dashboard_milk_analysis', ['model' => $model, 'id' => 'tbl_milk_analysis_grid_id', 'container' => 'tbl_milk_analysis_grid_container', 'table_class' => 'tbl_milk_analysis_grid', 'popup_title' => Yii::t('app', 'Milk Analysis'), 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'from_date_id' => 'milk_analysis_grid_from_date', 'to_date_id' => 'milk_analysis_grid_to_date', 'date_picker_class' => 'col-sm-3', 'shift_class' => 'col-sm-3']); ?>-->

            <?php //$this->render('', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'bmc_coll_widget_container', 'diff_sp_name' => 'bmc_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'bmc_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Milk Analysis')]); ?>
        </div>
    </div>
</div>
</div>