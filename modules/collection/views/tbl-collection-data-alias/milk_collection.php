<?php
$this->title = Yii::t('app', 'Milk Collection Approval');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'showFarmer' => $showFarmer]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'showFarmer' => $showFarmer, 'id' => $id, 'url' => $url, 'is_concate' => true]);
        ?>
    </div>
</div>

