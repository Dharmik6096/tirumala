<?php
$this->title = Yii::t('app', 'Mobile Dashboard Permission');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search_mapping', ['model' => $model]); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('form_widget_mapping', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'dataProviderOther' => $dataProviderOther, 'selectedArray' => $selectedArray, 'model' => $model]);
        ?>
    </div>
</div>
