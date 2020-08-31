<?php
$this->title = Yii::$app->label->title('create', 'Vehicle KM Information');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
