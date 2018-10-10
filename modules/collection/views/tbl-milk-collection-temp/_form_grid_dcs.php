<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collections Temp'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model]); ?>

            <?php
            $attr = [
                ['attribute' => 'dcs_code', 'filter' => false],
                ['attribute' => 'shift', 'filter' => false],
            ];
            $grid_option = [
                'id' => 'milk-coll-dcs-list',
                'attributes' => $attr,
                'active_column' => false,
            ];
            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['get-temp-data'], true);
            ?>
        </div>
    </div>
</div>