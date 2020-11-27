<?php
$this->title = 'Outward/Use Asset Transaction';
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search_transaction', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'txnModel' => $txnModel]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_transaction_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txnModel' => $txnModel]);
        ?>
    </div>
</div>
