<div class="row">            
    <div class="col-sm-12">
        <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'reconciliation_chart_widget_container', 'diff_sp_name' => 'reconciliation_chart_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'reconciliation_chart_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Reconciliation Chart')]); ?>
        <div id="reconciliation_chart_widget_container" class="cont"></div>
    </div>            
</div>