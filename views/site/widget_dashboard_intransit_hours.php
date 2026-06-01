<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader" id="intransit_title"><?= Yii::t('app', 'Intransit Analysis') . ' - ' . Yii::t('app', 'Empty Tanker'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_intransit_hours', ['model' => $model, 'id' => 'intransit_hours', 'container' => 'intransit_hours', 'date_range' => true, 'date_range_class' => 'col-sm-4', 'from_date' => date('d-m-Y'), 'to_date' => date('d-m-Y'), 'range2' => false, 'shift' => false, 'type' => 'column', 'hide_param' => 'test', 'title' => '', 'range_id1' => 'intransit_hours_dt1', 'range_id2' => 'intransit_hours_dt2','table_class' => 'intransit_hours','popup_title' => Yii::t('app', 'Intransit Hours')]); ?>
            <div id="intransit_hours_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>