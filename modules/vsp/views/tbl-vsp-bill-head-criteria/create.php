<?php
$this->title = Yii::$app->label->title('create', 'Bill Head Criteria');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
        ])
        ?>
    </div>
</div>