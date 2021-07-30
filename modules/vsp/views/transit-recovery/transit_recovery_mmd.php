<?php
$this->title = Yii::t('app', 'TS Loss And Shortage');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search_recovery', ['model' => $model, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_recovery_grid', ['model' => $model,
            'modelData' => $modelData, 'dataProvider' => $dataProvider, 'output' => $output]);
        ?>
    </div>
</div>
