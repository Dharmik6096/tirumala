<?php
$this->title = Yii::t('app', 'Delete MPP Collection');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, $action = 'delete-collection']); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_delete_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>
