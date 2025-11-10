<?php
$this->title = Yii::t('app', 'Delete Aadesh Master Applicability');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search_delete', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_delete_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>
