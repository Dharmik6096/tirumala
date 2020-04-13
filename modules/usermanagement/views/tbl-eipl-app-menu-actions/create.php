<?php
$this->title = Yii::t('app', 'Mobile Menu Permission');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $mappingModel]); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_form', ['model' => $model, 'selectedArray' => $selectedArray, 'model' => $model, 'menuArray' => $menuArray, 'mappingModel' => $mappingModel]);
        ?>
    </div>
</div>
