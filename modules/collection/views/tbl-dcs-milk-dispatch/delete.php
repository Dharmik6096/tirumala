<?php
$this->title = Yii::t('app', 'Delete Milk Dispatch');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Milk Dispatch</h4>
                </div>
                <div class="large-search hidden-print">
                    <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, $action = 'delete-collection']); ?>
                    <div class="clearfix"></div>
                    <?php
                    echo $this->render('_delete_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
