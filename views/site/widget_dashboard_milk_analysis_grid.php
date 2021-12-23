<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Analysis'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashboard_milk_analysis', ['model' => $model, 'id' => 'tbl_milk_analysis_grid_id', 'container' => 'tbl_milk_analysis_grid_container', 'table_class' => 'tbl_milk_analysis_grid', 'popup_title' => Yii::t('app', 'Milk Analysis'), 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'from_date_id' => 'milk_analysis_grid_from_date', 'to_date_id' => 'milk_analysis_grid_to_date', 'date_picker_class' => 'col-sm-3', 'shift_class' => 'col-sm-3']); ?>
        </div>
    </div>
</div>