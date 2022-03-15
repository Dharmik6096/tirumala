<?php
$this->title = Yii::$app->label->title('create', 'Inventory Transfer');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'create',
            'txModel' => $txModel,
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ])
        ?>
    </div>
</div>
