<div class="col-sm-6 <?= $display_rmrd?>">
    <div class="col-sm-12 text-center widget-tab bmc-compare active dashboard_widget_heading"><?= Yii::t('app', 'BMC Milk Collection'); ?></div>
    <div class="flt">
        <div id="bmc-compare">
            <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'bmc_union_comparison', 'url' => $chart_url, 'container' => 'bmc_union_comparison_container', 'date_range' => true, 'range2' => true, 'shift' => false, 'type' => 'column', 'title' => '', 'range_id1' => 'bmc_from_Date', 'range_id2' => 'bmc_to_Date', 'range_id3' => 'bmc_from_Date_2', 'range_id4' => 'bmc_to_Date_2', 'table_popup' => true, 'table_class' => 'bmc_union_comparison_data', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'BMC Milk Collection')]); ?>
            <div id="bmc_union_comparison_container" class="cont"></div>
        </div>
    </div>
</div>