<?php
$this->title = Yii::$app->label->title('create', 'Banner');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'applicability_model' => $applicability_model,
            'attachment' => $attachment,
            'type' => 'create',
        ])
        ?>
    </div>
</div>