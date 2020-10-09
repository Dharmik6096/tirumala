<div class="col-sm-12">
    <div class="cal-header"><?= Yii::t('app', 'Milk Collection'); ?></div>
    <div class="flt">
        <div id="society-compare">
            <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'table_milk_collection', 'url' => $container_url, 'container' => 'table_milk_collection', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'union_code' => true, 'plant_code' => true, 'mcc_code' => 'milk_coll_mcc', 'bmc_code' => 'milk_coll_bmc', 'dcs_code' => true, 'from_date_id' => 'milk_coll_from_date', 'to_date_id' => 'milk_coll_to_date', 'bmc_class' => 'col-sm-1', 'mcc_class' => 'col-sm-1', 'dcs_class' => 'col-sm-1', 'date_picker_class'=> 'col-sm-1', 'shift_class' => 'col-sm-1']); ?>
            <div id="table_milk_collection_container"  class="milk-collection mt0 cont"></div>
        </div>         
    </div>
</div>