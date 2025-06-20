<?php
$this->title = Yii::$app->label->title('create', 'Milk Quality Param Range');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="clearfix"></div>
        <?php
        if (!empty(Yii::$app->request->get())) {
            ?>
            <?=
            $this->render('_form', [
                'model' => $model,
                'animalDetail' => $animalDetail,
                'type' => $type,
                'existingRecords' => $existingRecords,
            ])
            ?>
        <?php } ?>
    </div>
</div>
