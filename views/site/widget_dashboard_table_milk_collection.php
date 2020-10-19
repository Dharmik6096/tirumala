<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Milk Collection'); ?></div>
    <div class="flt">
        <div id="society-comparea" class="padding-left-0">
            <!--, 'mcc_code' => 'milk_coll_mcc', 'bmc_code' => 'milk_coll_bmc', 'dcs_code' => true-->
            <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'table_milk_collection', 'url' => $container_url, 'container' => 'table_milk_collection', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'union_code' => false, 'plant_code' => false, 'from_date_id' => 'milk_coll_from_date', 'to_date_id' => 'milk_coll_to_date', 'bmc_class' => 'col-sm-3', 'mcc_class' => 'col-sm-3', 'dcs_class' => 'col-sm-3', 'date_picker_class'=> 'col-sm-3', 'shift_class' => 'col-sm-3','table_class' => 'table_milk_collection','popup_title' => Yii::t('app', 'Milk Collection')]); ?>
            <div id="table_milk_collection_container"  class="milk-collection mt0 cont"></div>
        </div>         
    </div>
</div>