<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'BIPL Smart Api Logs'));
?>
<div class="tbl-local-milk-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
            <div class="clearfix"></div>
            <?php
            if (!empty($dataProvider->getModels())) {
                echo $this->render('_repush_bulk_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);
            }
            ?>
        </div>
    </div>
</div>

