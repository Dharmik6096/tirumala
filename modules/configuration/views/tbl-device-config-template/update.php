<?php
$this->title = Yii::$app->label->title('edit', 'Device Configs');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'templateModel' => $templateModel,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>
