<?php
$this->title = Yii::$app->label->title('edit', 'blog');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
            'attachModel'=>$attachModel
        ])
        ?>
    </div>
</div>