<div class="col-sm-6">
    <?= $this->render('_dashborad_filter', ['model' => $model, 'id' => 'w0', 'type' => 'column', 'title' => '', 'url' => '', 'container' => 'milk_coll_widget_container', 'diff_sp_name' => 'milk_coll_widget', 'table_pop_up_only' => true, 'table_popup' => true, 'table_class' => 'milk_coll_widget', 'table_url' => $table_url, 'popup_title' => Yii::t('app', 'Milk Collection')]); ?>
    <div id="milk_coll_widget_container" class="cont">
        <!-- <div class="milk_coll_widget_container">
            <div id="milk_coll_widget_container" class="cont chart_type widget_two_bar_chart"></div>
            <div id="milk_coll_widget_container_table" class="cont table_type table-responsive widget_two_table_data">
                <table cellpadding="1" cellspacing="1" class="table milk_coll_widget_container">
                    <thead>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div> -->
    </div>
    <!-- <div id="container5" class="cont"></div> -->
</div>