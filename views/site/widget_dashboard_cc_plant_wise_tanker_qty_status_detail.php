<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader" id="cc_plant_wise_tanker_qty_status_title"><?= Yii::t('app', 'CC&Plant Wise Tanker Qty/Status Detail') . ' - ' . Yii::t('app', 'Total'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_cc_plant_wise_tanker_qty_status_detail', [
                    'model' => $model,
                    'id' => 'cc_plant_wise_tanker_qty_status_detail',
                    'container' => 'cc_plant_wise_tanker_qty_status_detail',
                    'date_range' => true,
                    'date_range_class' => 'col-sm-4',
                    'from_date' => date('d-m-Y'),
                    'to_date' => date('d-m-Y'),
                    'range2' => false,
                    'shift' => false,
                    'type' => 'column',
                    'hide_param' => 'test',
                    'title' => '',
                    'range_id1' => 'cc_plant_wise_tanker_qty_status_dt1',
                    'range_id2' => 'cc_plant_wise_tanker_qty_status_dt2',
                    'table_class' => 'cc_plant_wise_tanker_qty_status_detail_tbl',
                    'popup_title' => Yii::t('app', 'CC&Plant Wise Tanker Qty/Status Detail')
            ]); ?>
            <div id="cc_plant_wise_tanker_qty_status_detail_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>
