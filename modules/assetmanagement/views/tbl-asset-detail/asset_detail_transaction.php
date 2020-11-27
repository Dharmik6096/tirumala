<?php
$this->title = 'Add Inward Asset Transaction';
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search_transaction', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'datefilter' => TRUE]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_transaction_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'action' => 'inward-asset-transaction', 'txnModel' => $txnModel]);
        ?>
    </div>
</div>
