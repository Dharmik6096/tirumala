<?php
$this->title = Yii::$app->label->title('create', 'Data Consider in VSP Payment');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create'
        ])
        ?>
    </div>
</div>

