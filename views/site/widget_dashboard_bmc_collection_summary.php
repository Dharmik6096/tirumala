<div class="col-sm-6">
    <div class="cal-header"><?= Yii::t('app', 'BMC Collection Summary'); ?></div>
    <div class="flt">
        <div id="society-compare">
            <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'bmc_collection_summary', 'url' => $container_url, 'container' => 'bmc_collection_summary', 'from_date' => true, 'to_date' => false, 'from_shift' => true, 'to_shift' => false, 'from_date_id' => 'bmc_collection_summary_from_date', 'to_date_id' => 'coll_status_to_date', 'date_picker_class' => 'col-sm-3', 'shift_class' => 'col-sm-3']); ?>
            <div id="bmc_collection_summary_container"  class="milk-collection mt0 cont"></div>
        </div>         
    </div>
</div>    