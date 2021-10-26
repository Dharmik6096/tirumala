<?php
$this->title = Yii::$app->label->title('edit', 'Milk Collection Allow');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, $action = 'update-collection-allow']); ?>
        <div class="clearfix"></div>
        <?=
        $this->render('_update_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'detailModel' => $detailModel, 'config' => $config]);
        ?>

    </div>
</div>