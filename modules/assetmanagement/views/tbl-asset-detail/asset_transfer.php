<?php
$this->title = Yii::t('app', 'Asset Tranfer');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search_asset_transfer', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'txnModel' => $txnModel]); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_asset_transfer_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txnModel' => $txnModel]);
        ?>
    </div>
</div>
