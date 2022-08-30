<?php
$this->title = Yii::$app->label->title('create', 'App Lock Configs');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'saveModel' => $saveModel,
            'type' => 'create',
        ])
        ?>
    </div>
</div>