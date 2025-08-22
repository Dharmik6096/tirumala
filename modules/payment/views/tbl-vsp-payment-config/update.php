<?php
$this->title = Yii::$app->label->title('edit', 'DCS wise billing mapping');
?>
<div class="panel panel-default hide-grid-settings panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?= $this->render('_search_edit', ['model' => $searchModel, 'action' => 'update']) ?>
        <div class="clearfix"></div>
        <?= $this->render('_update_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>
    </div>
</div>
