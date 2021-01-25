<?php
$this->title = Yii::t('app', 'Bulk Data LOCK/UNLOCK');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_bulk_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_bulk_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>

