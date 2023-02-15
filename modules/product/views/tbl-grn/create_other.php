<?php
$this->title = Yii::$app->label->title('create', 'GRN');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_other', [
            'model' => $model,
            'type' => 'create',
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
        ])
        ?>
    </div>
</div>

