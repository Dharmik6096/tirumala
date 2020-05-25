<?php
$this->title = Yii::t('app', 'Config Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        <div class='hide-grid-settings panel_clear_both'>
            <?php
            echo $this->render('_form', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'selectedArray' => $selectedArray, 'model' => $model]);
            ?>
        </div>

    </div>
</div>
