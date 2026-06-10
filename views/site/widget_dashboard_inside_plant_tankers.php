<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader" id="inside_plant_title"><?= Yii::t('app', 'Inside Plant Tankers') . ' - ' . Yii::t('app', 'Waiting Tankers'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <?= $this->render('_dashborad_filter_inside_plant_tankers', [
                    'model' => $model,
                    'id' => 'inside_plant_tankers',
                    'container' => 'inside_plant_tankers',
                    'date_range' => true,
                    'date_range_class' => 'col-sm-4',
                    'from_date' => date('d-m-Y'),
                    'to_date' => date('d-m-Y'),
                    'range2' => false,
                    'shift' => false,
                    'type' => 'column',
                    'hide_param' => 'test',
                    'title' => '',
                    'range_id1' => 'inside_plant_dt1', 
                    'range_id2' => 'inside_plant_dt2', 
                    'table_class' => 'inside_plant_tankers_tbl',
                    'popup_title' => Yii::t('app', 'Inside Plant Tankers Status')
            ]); ?>

            <div id="inside_plant_tankers_container" class="cont milk-collection"></div>
        </div>
    </div>
</div>
