<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader" id="trip_wise_tanker_time_title"><?= Yii::t('app', 'Trip Wise Tanker Time Details') . ' - ' . Yii::t('app', 'Total Hours'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_trip_wise_tanker_time_details', [
                    'model' => $model,
                    'id' => 'trip_wise_tanker_time_details',
                    'container' => 'trip_wise_tanker_time_details',
                    'date_range' => true,
                    'date_range_class' => 'col-sm-4',
                    'from_date' => date('d-m-Y'),
                    'to_date' => date('d-m-Y'),
                    'range2' => false,
                    'shift' => false,
                    'type' => 'column',
                    'hide_param' => 'test',
                    'title' => '',
                    'range_id1' => 'trip_wise_tanker_time_dt1',
                    'range_id2' => 'trip_wise_tanker_time_dt2',
                    'table_class' => 'trip_wise_tanker_time_details_tbl',
                    'popup_title' => Yii::t('app', 'Trip Wise Tanker Time Details')
            ]); ?>

            <div id="trip_wise_tanker_time_details_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>
