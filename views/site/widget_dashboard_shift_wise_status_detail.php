<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader" id="shift_wise_status_title"><?= Yii::t('app', 'Shift wise Status Detail') . ' - ' . Yii::t('app', 'Cell Count'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_shift_wise_status_detail', [
                    'model' => $model,
                    'id' => 'shift_wise_status_detail',
                    'container' => 'shift_wise_status_detail',
                    'date_range' => true,
                    'date_range_class' => 'col-sm-4',
                    'from_date' => date('d-m-Y'),
                    'to_date' => date('d-m-Y'),
                    'range2' => false,
                    'shift' => false,
                    'type' => 'column',
                    'hide_param' => 'test',
                    'title' => '',
                    'range_id1' => 'shift_wise_status_dt1',
                    'range_id2' => 'shift_wise_status_dt2',
                    'table_class' => 'shift_wise_status_detail_tbl',
                    'popup_title' => Yii::t('app', 'Shift wise Status Detail')
            ]); ?>

            <div id="shift_wise_status_detail_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>
