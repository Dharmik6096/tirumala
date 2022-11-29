<?php
$this->title = Yii::$app->label->title('create', 'Device Configs');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'templateModel' => $templateModel,
            'type' => 'create',
        ])
        ?>
    </div>
</div>