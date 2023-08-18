<?php
$this->title = Yii::t('app', 'Milk Receipt - Edit Trip Detail');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php echo $this->render('_trip_detail_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
    </div>
</div>
