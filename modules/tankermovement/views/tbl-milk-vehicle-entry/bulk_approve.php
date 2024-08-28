<?php
$this->title = Yii::t('app', 'Milk Receipt Bulk Approval');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12">
                    <?php
                    echo $this->render('_bulk_data_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'milkVehicleEntryModel' => $milkVehicleEntryModel]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
