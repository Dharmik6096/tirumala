<div class="col-sm-6">
    <div class="cal-header">
        <?php
        $search_date = Yii::$app->controls->view_date($date);
        echo $search_date . ' ' . Yii::t('app', 'Dispatch vs Receipt');
        ?>
    </div>
    <div class="flt">
        <div id="society-compare">
            <?= $this->render('_dashborad_filter_rls', ['model' => $model, 'id' => 'dipatch_vs_receipt', 'url' => $container_url, 'container' => 'dipatch_vs_receipt', 'hidden_from_date' => $search_date, 'hidden_to_date' => $search_date, 'union_code' => true, 'mcc_code' => 'dispatch_vs_receipt', 'from_date_id' => 'coll_status_from_date', 'to_date_id' => 'coll_status_to_date', 'mcc_class' => 'col-sm-3']); ?>
            <div id="dipatch_vs_receipt_container"  class="milk-collection mt0 cont div_height495"></div>
        </div>         
    </div>
</div>