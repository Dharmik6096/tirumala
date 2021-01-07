<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Analysis'); ?></div>
        <div class="flt">
            <div id="society-compare" class="pl_0">
                <?= $this->render('_dashboard_milk_analysis', ['model' => $model, 'id' => 'tbl_milk_analysis_grid_id', 'container' => 'tbl_milk_analysis_grid_container', 'date_range' => true, 'date_range_class' => 'col-sm-8', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'table_class' => 'tbl_milk_analysis_grid', 'range_id_from' => 'from_date_milk_analysis_grid', 'range_id_to' => 'to_date_milk_analysis_grid','popup_title' => Yii::t('app', 'Milk Analysis')]); ?>
            </div>
        </div>
    </div>
</div>