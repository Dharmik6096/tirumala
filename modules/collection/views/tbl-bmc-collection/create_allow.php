<?php
$this->title = Yii::$app->label->title('create', 'BMC Collection Allow');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_allow', [
            'model' => $model,
            'type' => 'create',
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ])
        ?>
    </div>
</div>
