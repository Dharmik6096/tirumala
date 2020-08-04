<?php
$this->title = Yii::$app->label->title('create', 'Text Rate Formula');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_text_rate', [
            'model' => $model,
        ])
        ?>
    </div>
</div>