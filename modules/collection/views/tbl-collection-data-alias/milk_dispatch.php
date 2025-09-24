<?php
$this->title = Yii::t('app', 'Milk Dispatch Approval');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'id' => $id, 'url' => $url, 'is_concate' => false]);
        ?>
    </div>
</div>
