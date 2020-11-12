
<div class="col-sm-6">
    <div class="cal-header dashboardWidgetHeader"><?= Yii::t('app', 'Collection Status Manual vs Auto'); ?></div>
    <div class="flt">
        <div id="society-compare" class="pl_0">
            <!--, 'mcc_code' => 'coll_status_mcc', 'bmc_code' => 'coll_status_bmc'-->
            <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'manual_vs_auto_collection', 'url' => $container_url, 'container' => 'manual_vs_auto_collection', 'from_date' => true, 'to_date' => true, 'from_shift' => true, 'to_shift' => true, 'union_code' => false, 'plant_code' => false, 'from_date_id' => 'coll_status_from_date', 'to_date_id' => 'coll_status_to_date', 'date_picker_class' => 'col-sm-3', 'mcc_class' => 'col-sm-3', 'bmc_class' => 'col-sm-3','table_class' => 'manual_vs_auto_collection','popup_title' => Yii::t('app', 'Collection Status Manual vs Auto')]); ?>
            <div id="manual_vs_auto_collection_container"  class="milk-collection mt0 cont"></div>
        </div>         
    </div>
</div>    