<?php
$this->title = Yii::$app->label->title('create', 'Tax Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'data' => $data,
            'basic_tax' => $basic_tax
        ])
        ?>
    </div>
</div>
